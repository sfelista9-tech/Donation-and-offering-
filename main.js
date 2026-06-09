// Confirm before deleting
function confirmDelete(itemName) {
    return confirm('Are you sure you want to delete ' + itemName + '?');
}

// Format currency
function formatCurrency(value) {
    return new Intl.NumberFormat('en-TZ', {
        style: 'currency',
        currency: 'TZS'
    }).format(value);
}

// Display formatted currency in elements
document.addEventListener('DOMContentLoaded', function() {
    const currencyElements = document.querySelectorAll('[data-currency]');
    currencyElements.forEach(element => {
        const value = parseFloat(element.textContent);
        if (!isNaN(value)) {
            element.textContent = formatCurrency(value);
        }
    });
});

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (input.value.trim() === '') {
            input.style.borderColor = '#e74c3c';
            isValid = false;
        } else {
            input.style.borderColor = '#bdc3c7';
        }
    });

    return isValid;
}

// Clear form
function clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.style.borderColor = '#bdc3c7';
        });
    }
}

// Search/Filter functionality
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    const filter = input.value.toUpperCase();
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent || row.innerText;
        row.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
    }
}

// Print page
function printPage() {
    window.print();
}

// Export table to CSV
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const csvRow = [];
        cols.forEach(col => {
            csvRow.push(col.innerText);
        });
        csv.push(csvRow.join(','));
    });

    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.href = URL.createObjectURL(csvFile);
    downloadLink.download = filename;
    downloadLink.click();
}
