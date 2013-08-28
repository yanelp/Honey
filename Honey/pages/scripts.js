function trim(str) {
  return str.replace(/^\s+|\s+$/g,"");
}

function removeFila(t, campo){ 
	//alert('cecy');
	var td = t.parentNode;
	var tr = td.parentNode;
	var table = tr.parentNode;
	table.removeChild(tr);
	//document.getElementById('row_number_'+campo).value=parseInt(document.getElementById('row_number_'+campo).value)-1;

}


function insert_row(tabla, campo, valor){

	if(valor!=''){
		valor=trim(valor);
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
		tabla.tBodies[0].rows[max_row].cells[1].innerHTML='<input type="button" onClick="removeFila(this, '+campo2+')" value="X" />';
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


function insert_row_course(tabla, campo, valor){

	if(valor!=''){
		
		
		//busco si hay enter
		valor=escape(valor); //pone %0 en cada enter y %20 en cada espacio
		n=valor.search("%0"); //devuelve la pos donde encuentra el enter
		renglon=valor.substring(0,n);
		largo=valor.length;
		x=0;
		var escenario="";
		while(n!=-1){
		
			//me quedo con el primer renglon
			if(x!=0){renglon=valor.substring(0,n);}
			x=1;
			renglon=unescape(renglon);
			if(x==0){escenario=renglon;}
			else{escenario=escenario+renglon+'<br>';}
			valor=valor.substring(n+3,largo);
			n=valor.search("%0"); 
		}
		if(n=-1){
			renglon=unescape(renglon);
			renglon=valor.substring(0,largo);
			escenario=escenario+renglon;
		}
		escenario=unescape(escenario);
		insert_row(tabla, campo, escenario);
		
	}
	else{
	  alert('Must put a value');
	}
} 


function clean_symbol(){
	
}

function go_page(id_note, id_usecase, page){
  document.getElementById('form1').action=page+'&uc_note_id='+id_note+'&id_usecase='+id_usecase;
  document.getElementById('form1').submit();
}


