<table width="100%" class="table table-striped table-bordered">
   <thead>
      <tr>
         <th width="20%">Test Name</th>
         <th width="30%">Test Description</th>
         <th width="25%">Expected Value</th>
         <th width="25%">Returned Value</th>
      </tr>
   </thead>

   {rows}
   <tr>
      <td>{test_name}</td>
      <td>{notes}</td>
      <td>{res_datatype}</td>
      <td>{result}</td>
   </tr>
   {/rows}

</table>
