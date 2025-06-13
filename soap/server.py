from spyne import Application, rpc, ServiceBase, Unicode
from spyne.protocol.soap import Soap11
from spyne.server.wsgi import WsgiApplication
from wsgiref.simple_server import make_server
from datetime import datetime
import json
import os

# File untuk menyimpan data mahasiswa
DATA_FILE = 'students.json'

class StudentService(ServiceBase):
    """Service class untuk operasi manajemen mahasiswa."""

    def __init__(self):
        """Inisialisasi dan memuat data dari file."""
        super().__init__()
        self._ensure_data_file_exists()

    def _ensure_data_file_exists(self):
        """Memastikan file data ada, jika tidak membuat file kosong."""
        if not os.path.exists(DATA_FILE):
            with open(DATA_FILE, 'w') as f:
                json.dump([], f)

    def _load_students(self):
        """Memuat data mahasiswa dari file."""
        with open(DATA_FILE, 'r') as f:
            return json.load(f)

    def _save_students(self, students):
        """Menyimpan data mahasiswa ke file."""
        with open(DATA_FILE, 'w') as f:
            json.dump(students, f, indent=2)

    @rpc(Unicode, Unicode, Unicode, _returns=Unicode)
    def AddStudent(ctx, nama, nim, jurusan):
        """Menambahkan mahasiswa baru ke sistem.
        
        Args:
            nama: Nama lengkap mahasiswa
            nim: Nomor Induk Mahasiswa
            jurusan: Jurusan mahasiswa
            
        Returns:
            String konfirmasi penambahan mahasiswa
        """
        service = StudentService()
        
        # Validasi input
        if not nama or not nim or not jurusan:
            return "Error: Semua field (nama, NIM, jurusan) harus diisi!"
        
        if not nim.isdigit():
            return "Error: NIM harus berupa angka!"
        
        students = service._load_students()
        
        # Cek apakah NIM sudah ada
        if any(student['nim'] == nim for student in students):
            return f"Error: Mahasiswa dengan NIM {nim} sudah terdaftar!"
        
        # Tambahkan mahasiswa baru
        new_student = {
            'nama': nama,
            'nim': nim,
            'jurusan': jurusan,
            'tanggal_ditambahkan': datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        }
        
        students.append(new_student)
        service._save_students(students)
        
        return (f"Sukses: Mahasiswa {nama} dengan NIM {nim} dari jurusan {jurusan} "
                "berhasil ditambahkan.")

# Membuat aplikasi SOAP
application = Application([StudentService],
    tns='urn:student.service',
    in_protocol=Soap11(validator='lxml'),
    out_protocol=Soap11()
)

# Membungkus dengan WsgiApplication
wsgi_application = WsgiApplication(application)

if __name__ == '__main__':
    # Menjalankan server
    server = make_server('0.0.0.0', 8000, wsgi_application)
    print("Server SOAP berjalan di http://localhost:8000")
    print("WSDL tersedia di http://localhost:8000/?wsdl")
    server.serve_forever()