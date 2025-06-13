from zeep import Client

# URL WSDL dari server SOAP
WSDL_URL = 'http://localhost:8000/?wsdl'

def main():
    """Fungsi utama untuk menjalankan client."""
    print("Client SOAP untuk Manajemen Mahasiswa")
    print("-----------------------------------")
    
    # Membuat client SOAP
    client = Client(WSDL_URL)
    
    # Input data mahasiswa
    print("\nMasukkan data mahasiswa:")
    nama = input("Nama: ")
    nim = input("NIM: ")
    jurusan = input("Jurusan: ")
    
    try:
        # Memanggil operasi AddStudent di server
        response = client.service.AddStudent(nama, nim, jurusan)
        print("\nRespon dari server:")
        print(response)
    except Exception as e:
        print(f"\nError: {e}")

if __name__ == '__main__':
    main()