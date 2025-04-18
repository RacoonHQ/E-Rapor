document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterGrade = document.getElementById('filterGrade');
    let searchTimeout;

    // Set nilai awal dari URL jika ada
    const urlParams = new URLSearchParams(window.location.search);
    searchInput.value = urlParams.get('search') || '';
    filterGrade.value = urlParams.get('grade') || '';

    function resetToFirstPage() {
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('grade');
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }

    async function performSearch() {
        const searchText = searchInput.value.trim();
        const gradeFilter = filterGrade.value;

        try {
            const response = await fetch(`data_mahasiswa.php?ajax_search=1&search=${searchText}&grade=${gradeFilter}`);
            const students = await response.json();
            
            const tbody = document.getElementById('studentData');
            
            if (students.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">Tidak ada data yang sesuai dengan pencarian</td></tr>';
                return;
            }

            tbody.innerHTML = students.map(student => `
                <tr>
                    <td>${student.nim}</td>
                    <td>${student.nama}</td>
                    <td>${student.tugas}</td>
                    <td>${student.uts}</td>
                    <td>${student.uas}</td>
                    <td>${student.nilai_akhir.toFixed(2)}</td>
                    <td>${student.grade}</td>
                    <td>
                        <input type="checkbox" class="student-checkbox" data-student='${JSON.stringify(student)}'>
                        <a href="edit_student.php?nim=${student.nim}" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger delete-btn" data-nim="${student.nim}">Hapus</button>
                    </td>
                </tr>
            `).join('');

            // Reattach delete handlers
            attachDeleteHandlers();
        } catch (error) {
            console.error('Error:', error);
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchText = this.value.trim();
            
            if (searchText === '') {
                resetToFirstPage();
                return;
            }

            searchTimeout = setTimeout(() => {
                const url = new URL(window.location.href);
                url.searchParams.set('search', searchText);
                url.searchParams.set('page', '1');
                window.location.href = url.toString();
            }, 300);
        });
    }

    if (filterGrade) {
        filterGrade.addEventListener('change', function() {
            const gradeValue = this.value;
            const url = new URL(window.location.href);
            
            if (gradeValue) {
                url.searchParams.set('grade', gradeValue);
            } else {
                url.searchParams.delete('grade');
            }
            
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        });
    }

    function attachDeleteHandlers() {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const nim = this.dataset.nim;
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `delete_student.php?nim=${nim}`;
                    }
                });
            });
        });
    }

    // Tambahkan fungsi untuk select all
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    function printSelectedData() {
        const selectedStudents = Array.from(document.querySelectorAll('.student-checkbox:checked'))
            .map(checkbox => JSON.parse(checkbox.dataset.student));

        if (selectedStudents.length === 0) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Silakan pilih data yang ingin dicetak',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Populate print section
        const printTableBody = document.getElementById('printTableBody');
        const mainContent = document.querySelector('.container');
        const printSection = document.getElementById('printSection');
        
        // Update print content
        printTableBody.innerHTML = selectedStudents.map(student => `
            <tr>
                <td>${student.nim}</td>
                <td>${student.nama}</td>
                <td>${student.tugas}</td>
                <td>${student.uts}</td>
                <td>${student.uas}</td>
                <td>${student.nilai_akhir.toFixed(2)}</td>
                <td>${student.grade}</td>
            </tr>
        `).join('');

        // Hide main content, show print section
        mainContent.style.display = 'none';
        printSection.style.display = 'block';

        // Print
        window.print();

        // Restore original view
        mainContent.style.display = 'block';
        printSection.style.display = 'none';
    }

    function initializeEventHandlers() {
        // Reattach all event listeners
        attachDeleteHandlers();
        
        // Print button
        document.getElementById('printBtn')?.addEventListener('click', printSelectedData);
        
        // Select all checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.student-checkbox')
                    .forEach(cb => cb.checked = this.checked);
            });
        }

        // Search and filter handlers
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchText = this.value.trim();
                
                if (searchText === '') {
                    resetToFirstPage();
                    return;
                }

                searchTimeout = setTimeout(() => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', searchText);
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                }, 300);
            });
        }

        if (filterGrade) {
            filterGrade.addEventListener('change', function() {
                const gradeValue = this.value;
                const url = new URL(window.location.href);
                
                if (gradeValue) {
                    url.searchParams.set('grade', gradeValue);
                } else {
                    url.searchParams.delete('grade');
                }
                
                url.searchParams.set('page', '1');
                window.location.href = url.toString();
            });
        }
    }

    // Initialize all handlers on page load
    initializeEventHandlers();
});
