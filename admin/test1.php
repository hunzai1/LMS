<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Teacher Viewer</title>
  <style>
    body { font-family: Arial; padding: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    .error { color: red; }
  </style>
</head>
<body>

  <h1>Teacher List</h1>
  <button onclick="loadTeachers()">Load Teachers</button>

  <div id="teacher-list"></div>

  <script>
    function loadTeachers() {
      fetch('fetch_teachers.php')
        .then(response => response.json())
        .then(data => 
        {

          const container = document.getElementById('teacher-list');
          

          if (data.length === 0) 
          {
            container.innerHTML = '<p>No teachers found.</p>';
            return;
          }


          else
          {
            let table = '<table><tr><th>ID</th><th>Name</th><th>Email</th><th>contact number </th><th>Gender</th></tr>';
            
             data.forEach(teach => {
              table += `<tr>
             <td>${teach.teacher_id}</td>
             <td>${teach.first_name} ${teach.last_name}</td>
             <td>${teach.teachers_email}</td>
              <td>${teach.contact_number}</td>
              <td>${teach.gender}</td>
                </tr>`;
                        });

                          table += '</table>';
                       container.innerHTML = table;

          }

        })

        .catch(error => {
          console.error('Fetch error:', error);
          document.getElementById('teacher-list').innerHTML = '<p class="error">Error loading teachers.</p>';
        });
    }
  </script>

</body>
</html>
