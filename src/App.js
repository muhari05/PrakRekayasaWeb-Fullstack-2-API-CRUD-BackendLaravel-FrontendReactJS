import React, { useState, useEffect } from "react";
import axios from "axios";
import './App.css';

const API_URL = "http://127.0.0.1:8000/api/mahasiswa"; // URL backend Laravel
const TOKEN = "Bearer Token-Rizal"; // Token Bearer dari backend

function App() {
  const [mahasiswa, setMahasiswa] = useState([]);
  const [form, setForm] = useState({ nim: "", nama_mahasiswa: "", fakultas: "", jurusan: "" });
  const [editId, setEditId] = useState(null); // ID data yang sedang diedit
  const [message, setMessage] = useState("");

  // Fetch data mahasiswa
  const fetchMahasiswa = async () => {
    try {
      const response = await axios.get(API_URL, { headers: { Authorization: TOKEN } });
      setMahasiswa(response.data.data || []);
    } catch (error) {
      setMessage("Error fetching data: " + error.message);
    }
  };

  useEffect(() => {
    fetchMahasiswa();
  }, []);

  // Handle form input
  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  // Tambahkan mahasiswa baru atau update mahasiswa
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editId) {
        // Update data
        const response = await axios.put(`${API_URL}/${editId}`, form, {
          headers: { Authorization: TOKEN },
        });
        setMessage(response.data.pesan || "Berhasil mengupdate data!");
      } else {
        // Tambahkan data baru
        const response = await axios.post(API_URL, form, {
          headers: { Authorization: TOKEN },
        });
        setMessage(response.data.pesan || "Berhasil menambahkan data!");
      }
      setForm({ nim: "", nama_mahasiswa: "", fakultas: "", jurusan: "" });
      setEditId(null);
      fetchMahasiswa();
    } catch (error) {
      setMessage("Error: " + error.response?.data?.message || error.message);
    }
  };

  // Hapus mahasiswa
  // Hapus mahasiswa dengan konfirmasi
const handleDelete = async (id) => {
  const confirmDelete = window.confirm("Apakah Anda yakin menghapus data ini?");
  if (!confirmDelete) {
    setMessage("Penghapusan data dibatalkan.");
    return;
  }

  try {
    const response = await axios.delete(`${API_URL}/${id}`, {
      headers: { Authorization: TOKEN },
    });
    setMessage(response.data.pesan || "Berhasil menghapus data!");
    fetchMahasiswa();
  } catch (error) {
    setMessage("Error: " + error.response?.data?.message || error.message);
  }
};


  // Set data mahasiswa untuk diedit
  const handleEdit = (mhs) => {
    setForm({
      nim: mhs.nim,
      nama_mahasiswa: mhs.nama_mahasiswa,
      fakultas: mhs.fakultas,
      jurusan: mhs.jurusan,
    });
    setEditId(mhs.id);
    setMessage("Mengedit data mahasiswa...");
  };

  return (
    
    <div className="container">
      
      <h1>CRUD Data Mahasiswa(Full Stack 2)</h1>
      <h2>(API+FRONTED Backend)</h2>


      {/* Form Tambah/Edit Mahasiswa */}
      <form onSubmit={handleSubmit}>
        <input
      
          name="nim"
          placeholder="NIM"
          value={form.nim}
          onChange={handleChange}
          required
        />
        <input
          name="nama_mahasiswa"
          placeholder="Nama Mahasiswa"
          value={form.nama_mahasiswa}
          onChange={handleChange}
          required
        />
        {/* Dropdown Fakultas */}
        <select
          name="fakultas"
          value={form.fakultas}
          onChange={handleChange}
          required
        >
          <option value="" disabled>Pilih Fakultas</option>
          <option value="FTIK">FTIK</option>
          <option value="Psikologi">Psikologi</option>
          <option value="Hukum">Hukum</option>
          <option value="Ekonomi">Ekonomi</option>
          <option value="Teknik">Teknik</option>
        </select>
        <input
          name="jurusan"
          placeholder="Jurusan"
          value={form.jurusan}
          onChange={handleChange}
          required
        />
        <button type="submit">
          {editId ? "Update Mahasiswa" : "Tambah Mahasiswa"}
        </button>
        {editId && (
          <button
            type="button"
            onClick={() => {
              setForm({ nim: "", nama_mahasiswa: "", fakultas: "", jurusan: "" });
              setEditId(null);
              setMessage("Batal mengedit data mahasiswa.");
            }}
          >
            Batal
          </button>
        )}
      </form>

      {/* Pesan Notifikasi */}
      {message && <p>{message}</p>}

      {/* Tabel Mahasiswa */}
      <table border="1">
        <thead>
          <tr>
            <th>ID</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Fakultas</th>
            <th>Jurusan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          {mahasiswa.length > 0 ? (
            mahasiswa.map((mhs) => (
              <tr key={mhs.id}>
                <td>{mhs.id}</td>
                <td>{mhs.nim}</td>
                <td>{mhs.nama_mahasiswa}</td>
                <td>{mhs.fakultas}</td>
                <td>{mhs.jurusan}</td>
                <td>
                  <button onClick={() => handleEdit(mhs)}>Edit</button>
                  <button onClick={() => handleDelete(mhs.id)}>Hapus</button>
                </td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="6">Data kosong</td>
            </tr>
          )}
        </tbody>
      </table>
      {/* Total Jumlah Data */}
      <center><p>Total jumlah data mahasiswa: {mahasiswa.length}</p></center>
    </div>
  );
}

export default App;
