function insert_row(tabla, campo, valor){

	if(valor!=''){
	
		//insertar nueva fila al final
		var tabla = document.getElementById(tabla);
		
		var max_row=tabla.tBodies[0].rows.length;

		tabla.tBodies[0].insertRow(max_row);
		// Crear la columna de tipo <td>
		var cabecera = document.createElement("td");
		cabecera.setAttribute('scope', 'row');
		cabecera.innerHTML = valor;
		tabla.tBodies[0].rows[max_row].appendChild(cabecera); 
		tabla.tBodies[0].rows[max_row].insertCell(1);	
		var campo2="'"+campo+"'";
		tabla.tBodies[0].rows[max_row].cells[1].innerHTML='<input type="button" onclick="remove(this, '+campo2+')" value="X" />';
		tabla.tBodies[0].rows[max_row].insertCell(2);		
		var nombre=campo+max_row;
		var valor2="'"+valor+"'";
		tabla.tBodies[0].rows[max_row].cells[2].innerHTML="<input type='hidden' name="+nombre+" value="+valor2+" />";

		document.getElementById(campo).value='';
		document.getElementById('row_number_'+campo).value=parseInt(document.getElementById('row_number_'+campo).value)+1;
		
	}
	else{
	  alert('Must put a value');
	}
}

function remove(t, campo)
{
	//campo='symbol_synonymous'
	//alert(campo);
	var td = t.parentNode;
	var tr = td.parentNode;
	var table = tr.parentNode;
	table.removeChild(tr);
	document.getElementById('row_number_'+campo).value=parseInt(document.getElementById('row_number_'+campo).value)-1;
}


function clean_symbol(){
	
}
