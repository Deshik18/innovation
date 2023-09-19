const gridOptions = {
    columnDefs: [
        { headerName: 'FY', field: 'fy', editable: true,cellEditor: 'agSelectCellEditor',cellEditorParams: {values: [], } , },
        { headerName: 'Category', field: 'category'},
        { headerName: 'Department', field: 'dept'},
        { headerName: 'Scheme Name', field: 'scheme_name'},
        { headerName: 'Scheme Code', field: 'scheme_code',},
        { headerName: 'AB', field: 'ab'},
        { headerName: 'AGB', field: 'agb'},
        { headerName: 'RB', field: 'rb'},
        { headerName: 'RGB', field: 'rgb'},
        { headerName: 'BE', field: 'be'},
        { headerName: 'GBE', field: 'gbe'},
        { headerName: 'Scheme Major Objective', field: 'scheme_major_obj', filter: false},
        { headerName: 'SDG', field: 'sdg', filter: 'agTextColumnFilter', filterParams:{ values: [],}},
        { headerName: 'Activity', field: 'activity', filter: 'agTextColumnFilter', filterParams:{ values: [],}},
        
      // Add more columns as needed...
    ],
    defaultColDef: {
        flex: 1,
        minWidth: 200,
        resizable: true,
        floatingFilter: true,
        sortable: true,
    },
    onCellValueChanged: onCellValueChanged,
};

function onCellValueChanged(event) {
    // This function will be called when a cell value is changed
    console.log('Cell value changed:', event);
    if (event.oldValue !== event.newValue) {
        // Send the updated data to the server for database update
        // You should implement the AJAX request or form submission here
        fetch('update_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                rowId: event.data.sno, // Replace with the appropriate identifier
                field: event.colDef.field,
                newValue: event.newValue,
            }),
        })
            .then((response) => response.json())
            .then((updatedData) => {
                // Handle the server response if needed
                console.log('Data updated:', updatedData);
            })
            .catch((error) => {
                console.error('Error updating data:', error);
            });
    }
}

fetch('data_fy.php') // Replace with your PHP script to fetch data
.then((response) => response.json())
.then((validFyValues) => {
    // Set "fy" values for the agRichSelectCellEditor
    gridOptions.columnDefs.find((colDef) => colDef.field === 'fy').cellEditorParams.values = validFyValues;

    // Fetch additional data for the ag-Grid if needed
    fetch('data.php') // Replace with your PHP script
        .then((response) => response.json())
        .then((data) => {
            gridOptions.api.setRowData(data);
        })
        .catch((error) => {
            console.error('Error fetching additional data:', error);
        });
})
.catch((error) => {
    console.error('Error fetching "fy" data:', error);
});

// Create the ag-Grid
var gridDiv = document.querySelector('#myGrid');
new agGrid.Grid(gridDiv, gridOptions);
