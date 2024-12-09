<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <style>
      /* Modal styling */
        #userModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        #userModal > div {
            background: #ffffff; /* White background for the form */
            padding: 20px 30px; /* Inner spacing */
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            width: 400px; /* Fixed width for the modal */
            text-align: center; /* Center text alignment */
            animation: fadeIn 0.3s ease-out; /* Optional: Fade-in animation */
        }

        #userModal h3 {
            margin-bottom: 20px;
            color: #333; /* Text color for the title */
        }

        #userModal .form-label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #555; /* Subtle text color for labels */
            font-weight: bold;
        }

        #userModal input, 
        #userModal select {
            width: 100%; /* Full width */
            padding: 10px; /* Inner padding */
            margin-bottom: 15px; /* Space between fields */
            border: 1px solid #ccc; /* Subtle border */
            border-radius: 5px; /* Rounded corners */
            font-size: 14px; /* Font size for inputs */
        }

        #userModal input:focus, 
        #userModal select:focus {
            border-color: #4CAF50; /* Highlighted border on focus */
            outline: none; /* Remove outline */
        }

        #userModal button {
            width: 48%; /* Buttons take half width with spacing */
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease; /* Smooth background color transition */
        }

        #userModal .btn-primary {
            background: #4CAF50; /* Green button */
            color: #fff;
        }

        #userModal .btn-primary:hover {
            background: #45a049; /* Darker green on hover */
        }

        #userModal .btn-secondary {
            background: #f44336; /* Red button */
            color: #fff;
        }

        #userModal .btn-secondary:hover {
            background: #e53935; /* Darker red on hover */
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
/* paginate */
                .pagination {
            display: flex;
            justify-content: center;
            list-style-type: none;
            padding: 0;
        }

        .pagination .page-item {
            margin: 0 5px;
        }

        .pagination .page-item a {
            display: inline-block;
            padding: 8px 12px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .pagination .page-item a:hover {
            background-color: #f0f0f0;
        }

        .pagination .page-item.active a {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
        <button class="btn btn-primary" onclick="logout()">Logout</button>
        <button class="btn btn-success" onclick="createUser()">Create</button>
    </div>
<div class="container mt-5">
    <input type="text" id="searchInput" class="form-control" placeholder="Search by name, job, or address...">
    <table class="table table-striped mt-3" id="searchResults">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>jenis kelamin</th>
                <th>jabatan</th>
                <th>alamat</th>
                <th>tanggal lahir</th> 
                <th>aksi</th> 
            </tr>
        </thead>
        <tbody>
            <!-- Results will be rendered here -->
        </tbody>
    </table>
    <nav>
        <ul class="pagination" id="pagination">
            <!-- Pagination buttons will be added here -->
        </ul>
    </nav>
</div>
<div id="userModal" style="display: none;">
        <div style="background: white; padding: 20px; border-radius: 10px; width: 400px;">
            <h3 id="modalTitle"></h3>
            <form id="userForm">
                <input type="hidden" id="userId">
                <input type="hidden" id="user_id">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" class="form-select" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="job" class="form-label">Job Position</label>
                    <input type="text" id="job" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" id="address" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="birthDate" class="form-label">Birth Date</label>
                    <input type="date" id="birthDate" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

<script>
     const apiURL = 'http://127.0.0.1:8000/api/karyawan';
     const token = localStorage.getItem('access_token');
        let cachedData = [];
        let isDataLoaded = false;

        if (!token) {
            window.location.href = '/login';
        }

        // Fetch and render table
        function fetchData() {
            fetch(apiURL, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data.data)) {
                    cachedData = data.data;
                    isDataLoaded = true;
                    renderTable(cachedData);
                } else {
                    console.error('Invalid data format');
                }
            })
            .catch(err => console.error('Error fetching data:', err));
        }

        // Render table rows
        function renderTable(data) {
            const tableBody = document.querySelector('#searchResults tbody');
            tableBody.innerHTML = '';
            data.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.nama}</td>
                    <td>${user.jeniskelamin}</td>
                    <td>${user.jabatan}</td>
                    <td>${user.alamat}</td>
                    <td>${user.tgl_lahir}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editUser(${user.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

                // Fetch data with pagination
        function fetchData(page = 1) {
            fetch(`${apiURL}?page=${page}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                renderTable(data.data); // Render data rows
                renderPagination(data); // Render pagination links
            })
            .catch(err => console.error('Error fetching data:', err));
        }


        // Render pagination links
        function renderPagination(data) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (data.current_page > 1) {
                pagination.innerHTML += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="fetchData(${data.current_page - 1})">Previous</a>
                    </li>
                `;
            }

            for (let i = 1; i <= data.last_page; i++) {
                pagination.innerHTML += `
                    <li class="page-item ${data.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="fetchData(${i})">${i}</a>
                    </li>
                `;
            }

            if (data.current_page < data.last_page) {
                pagination.innerHTML += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="fetchData(${data.current_page + 1})">Next</a>
                    </li>
                `;
            }
        }

        // Initialize data
        fetchData();
         
        // Tangani submit form add or edit
        document.getElementById('userForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const token = localStorage.getItem('access_token');
                const id = document.getElementById('userId').value;
                const url = id 
                    ? `http://127.0.0.1:8000/api/karyawan/edit/${id}` 
                    : 'http://127.0.0.1:8000/api/karyawan/store';

                const method = id ? 'PUT' : 'POST';

                const body = {
                    nama: document.getElementById('name').value,
                    jeniskelamin: document.getElementById('gender').value,
                    jabatan: document.getElementById('job').value,
                    alamat: document.getElementById('address').value,
                    tgl_lahir: document.getElementById('birthDate').value
                };

                fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(body)
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    closeModal();
                    location.reload(); // Reload halaman setelah operasi selesai
                })
                .catch(error => console.error('Error:', error));
            });

         // Create user
         function createUser() {
            openModal(false);
        }
        // fungsi untuk editUser
        function editUser(id) {
            const token = localStorage.getItem('access_token');
            fetch(`http://127.0.0.1:8000/api/karyawan/show/${id}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            })
            
            .then(response => response.json())
            .then(json => {
            const userData = json.data; // Jika data berada di dalam json.data
            openModal(true, userData);
        })
            
            .catch(error => console.error('Error:', error));
        }
        // fungsi untuk open modal form
        function openModal(isEdit, userData = {}) {
            document.getElementById('userModal').style.display = 'block';
            document.getElementById('modalTitle').innerText = isEdit ? 'Edit Employee' : 'Create Employee';
            
            document.getElementById('userId').value = isEdit && userData.id ? userData.id : '';
            document.getElementById('name').value = isEdit && userData.nama ? userData.nama : '';
            document.getElementById('gender').value = isEdit && userData.jeniskelamin ? userData.jeniskelamin : '';
            document.getElementById('job').value = isEdit && userData.jabatan ? userData.jabatan : '';
            document.getElementById('address').value = isEdit && userData.alamat ? userData.alamat : '';
            document.getElementById('birthDate').value = isEdit && userData.tgl_lahir ? userData.tgl_lahir : '';
        }

        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
            document.getElementById('userForm').reset();
        }



        // Fungsi untuk hapus
       function deleteUser(id) {   
            if (confirm('Are you sure you want to delete this user?')) {
                const token = localStorage.getItem('access_token');
                fetch(`http://127.0.0.1:8000/api/karyawan/delete/${id}`, {                    
                    method: 'DELETE',
                    headers: {
                                'Authorization': `Bearer ${token}`,
                                'Content-Type': 'application/json'
                            }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Reload halaman setelah delete
                })
                .catch(error => console.error('Error:', error));
            }
        }
        // fungsi keluar
        function logout() {
            const token = localStorage.getItem('access_token');

            fetch('http://127.0.0.1:8000/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                localStorage.removeItem('access_token');
                window.location.href = "{{ url('/login') }}";
            })
            .catch(error => console.error('Error:', error));
        }
        
// fungsi pencarian data
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const query = this.value; // Ambil nilai input
        const token = localStorage.getItem('access_token'); // Jika membutuhkan autentikasi

        fetch(`http://127.0.0.1:8000/api/karyawan/search?query=${query}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`, // Tambahkan jika ada token
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#searchResults tbody');
                tableBody.innerHTML = ''; // Kosongkan tabel sebelumnya

                // Render hasil pencarian ke tabel
                data.data.forEach(user => {
                    const row = `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.nama}</td>
                            <td>${user.jeniskelamin}</td>
                            <td>${user.jabatan}</td>
                            <td>${user.alamat}</td>
                            <td>${user.tgl_lahir}</td>
                             <td>
                                <button class="btn btn-warning btn-sm" onclick="editUser(${user.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    });
</script>


</body>
</html>
