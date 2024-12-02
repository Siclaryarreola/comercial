document.getElementById('searchUser').addEventListener('keyup', function() 
{
    var search = this.value.toLowerCase();
    var rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => 
        {
        row.style.display = row.textContent.toLowerCase().includes(search) ? '' : 'none';
    });
});