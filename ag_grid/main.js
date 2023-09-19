const gridOptions = {
    columnDefs: [
      { headerName: 'FY', field: 'fy', filter: 'agSetColumnFilter'},
      { headerName: 'Category', field: 'category', filter: 'agSetColumnFilter'},
      { headerName: 'Department', field: 'dept', filter: 'agSetColumnFilter'},
      { headerName: 'Scheme Name', field: 'scheme_name', filter: 'agSetColumnFilter'},
      { headerName: 'Scheme Code', field: 'scheme_code', filter: 'agTextColumnFilter'},
      { headerName: 'AB', field: 'ab', filter: 'agNumberColumnFilter'},
      { headerName: 'AGB', field: 'agb', filter: 'agNumberColumnFilter'},
      { headerName: 'RB', field: 'rb', filter: 'agNumberColumnFilter'},
      { headerName: 'RGB', field: 'rgb', filter: 'agNumberColumnFilter'},
      { headerName: 'BE', field: 'be', filter: 'agNumberColumnFilter'},
      { headerName: 'GBE', field: 'gbe', filter: 'agNumberColumnFilter'},
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
};

  // Fetch data from the PHP script and populate the grid
fetch('data.php')
.then((response) => response.json())
.then((data) => {
    gridOptions.api.setRowData(data);

  // Fetch filter values from PHP script for gb_activity for 'activity' column
    fetch('data_activity.php')
        .then((response) => response.json())
        .then((filterValues) => {
            // Get the column definition for the comma-separated string column
            const columnDef = gridOptions.columnDefs.find((def) => def.field === 'activity');

            // Update the filter values in the column definition
            columnDef.filterParams.values = filterValues;

            // Redraw the grid to reflect the updated filter values
            gridOptions.api.refreshHeader();
        })
        .catch((error) => {
            console.error('Error fetching filter values:', error);
        });
  fetch('data_sdg.php')
    .then((response) => response.json())
    .then((sdgValues) => {
      // Get the column definition for the 'sdg' column
      const columnDef = gridOptions.columnDefs.find((def) => def.field === 'sdg');

      // Update the filter values in the column definition
      columnDef.filterParams.values = sdgValues;

      // Redraw the grid to reflect the updated filter values
      gridOptions.api.refreshHeader();
    })
    .catch((error) => {
      console.error('Error fetching filter values for sdg:', error);
    });
})
.catch((error) => {
  console.error('Error fetching data:', error);
});

// Create the grid
new agGrid.Grid(document.querySelector('#myGrid'), gridOptions);
