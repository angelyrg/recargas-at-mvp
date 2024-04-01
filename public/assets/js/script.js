
$("#searchClientForm").on('submit', (e)=>{
    e.preventDefault();

    const inputPlayerID = $("#playerid").val();
    searchCliente(inputPlayerID);
})

function searchCliente(playerID) {
    const url = BASE_PATH + '/cliente/search';

    $.ajax({
        url: url,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ playerID: playerID }),
        beforeSend: function() {
            $('#btnBuscar').prop('disabled', true);
            updateClientHistorialUI({});
        },
        success: function(data) {
            updateClientInfoUI(data);
            const clientId = data.id;
            getHistorial(clientId);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            updateClientInfoUI({});
        },
        complete: function(data) {
            $('#btnBuscar').prop('disabled', false);
            
        }
    });
}

function getHistorial(client_id) {
    const url = BASE_PATH + '/recarga/historial';
    $.ajax({
        url: url,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ client_id }),
       
        success: function(data) {
            console.log(data.data);
            updateClientHistorialUI(data.data);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            updateClientHistorialUI({})
        }
    });
}


// TODO:  (DRY) <= searchCliente()
function searchClienteByID(client_id) {
    const url = BASE_PATH + '/cliente/show';

    $.ajax({
        url: url,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ client_id: client_id }),
        success: function(data) {
            updateClientInfoUI(data);
            const clientId = data.id;
            getHistorial(clientId);            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
        }
    });
}


function updateClientInfoUI(clientData){
    if( Object.keys(clientData).length === 0 ){

        const resultHTML = `
            <div class="card mt-3 p-4 text-center">
                <p>No se encontraron resultados</p>
            </div>`;
        $("#searchResult").html(resultHTML);
        
    }else{
        
        const clientCardHTML = `
            <div class="card mt-3 p-4 text-center">
                <div class="mx-auto mb-2 user_avatar">${ clientData.nombres.charAt(0)}</div>
                <p class="client_name mb-0">${clientData.nombres}  ${clientData.apellidos}</p>
                <p class="client_id">ID: ${clientData.playerID}</p>
                <div class="saldo_group">
                    <p class="mb-0">Saldo:</p>
                    <p class="fw-bold">S/ ${clientData.saldo}</p>
                </div>
                <button class="btnRecargar" data-bs-toggle="modal" data-bs-target="#recargarSaldoModal">Recargar</button>
            </div>`;
        $("#searchResult").html(clientCardHTML);
        // Update modal id
        $("#client_id").val(clientData.id);
    }

}

function updateClientHistorialUI(historialData){

    if( Object.keys(historialData).length === 0 ){
        const tableBody = `<tr><td colspan="4">No hay registros.</td></tr>`;
        $("#historialTableBody").html('');
        console.log("No hay datOs");

    }else{
        let tableBody = ``;

        historialData.forEach(item => {
            const tr = `<tr>
                <td>S/${item.monto}</td>
                <td>${item.banco}</td>
                <td>${item.canal_comunicacion}</td>
                <td>${item.created_at}</td>
                <td>
                    <button class="btn btn-sm btn-outline-dark" onclick="showVoucher('${item.voucher_image}')">Voucher</button>
                    <button class="btn btn-sm btn-outline-dark" onclick="searchRecargaById(${item.id})">Editar</button>
                </td>
            </tr>`;
            tableBody += tr;
        });

        console.log("Sí hay datOs");
        
        $("#historialTableBody").html(tableBody);
        // $('#historialTable').DataTable({destroy: true});
        
    }

}


function closeModal(modalSelector){
    $(modalSelector).modal("hide");
}

function resetForm(formSelector){
    $(formSelector)[0].reset();    
}

$("#recargarSaldoModal").on('hidden.bs.modal', function(){
    resetForm("#recargarSaldoForm");
});

$("#recargarSaldoModal").on('shown.bs.modal', function(){
    $('#montoRecargar').focus();
});


$("#recargarSaldoForm").on('submit', (e)=>{
    e.preventDefault();
    const url = BASE_PATH + '/recarga/store';
    sendForm('recargarSaldoForm', url, "#btnRecargar");
})

$("#corregirRecargaForm").on('submit', (e)=>{
    e.preventDefault();
    const url = BASE_PATH + '/recarga/update';
    sendUpdateForm('corregirRecargaForm', url, "#btnEditRecarga");
})

function sendForm(formId, url, submitBtnSelector) {
    let form = $('#' + formId)[0];
    let formData = new FormData(form);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $(submitBtnSelector).prop('disabled', true);
        },
        success: function(data) {
            if (data.success) {
                console.log(data);

                resetForm("#recargarSaldoForm");
                closeModal("#recargarSaldoModal");

                const resume = data.resume;

                const bodyContent = createRecargaResumenUI({
                    monto_recargado: resume.monto,
                    banco: resume.banco,
                    canal_comunicacion: resume.canal_comunicacion,
                    voucher_image_path: './../../public/uploads/' + resume.voucher_image,
                });

                showInfoModal('Success', bodyContent);

                searchClienteByID(resume.cliente_id);
            } else {
                showInfoModal('Error', data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        },
        complete: function(){
            $(submitBtnSelector).prop('disabled', false);
        }
    });
}

function sendUpdateForm(formId, url, submitBtnSelector) {
    let form = $('#' + formId)[0];
    let formData = new FormData(form);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $(submitBtnSelector).prop('disabled', true);
        },
        success: function(data) {
            console.log(data);

            if (data.success) {

                resetForm("#corregirRecargaForm");
                closeModal("#corregirRecargaModal");

                const resume = data.resume;

                const bodyContent = createRecargaResumenUI({
                    monto_recargado: resume.monto,
                    banco: resume.banco,
                    canal_comunicacion: resume.canal_comunicacion,
                    voucher_image_path: '',
                });

                showInfoModal('Recarga actualizado', bodyContent);
                console.log("Recarga actualizada!", resume);
                searchClienteByID(data.resume.cliente_id);

            } else {
                showInfoModal('Error!', data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('error!:', error);
            
        },
        complete: function(){
            $(submitBtnSelector).prop('disabled', false);
        }
    });
}


function showInfoModal(title, content){
    $("#alert_title").text(title); 
    $("#alert_body").html(content);
    $("#alertModal").modal('show');
}

function createRecargaResumenUI({monto_recargado, banco, canal_comunicacion, voucher_image_path}) {
    const resumeTemplate = `<div class="text-center">
        <div class="mb-3">
            <p class="mb-0">Monto recargado:</p>
            <p class="fw-bold fs-5 color-brand">S/ ${monto_recargado}</p>
        </div>

        <div class="mb-3">
            <p class="mb-0">Banco:</p>
            <p class="fw-bold">${banco}</p>
        </div>

        <div class="mb-3">
            <p class="mb-0">Canal de comunicación:</p>  
            <p class="fw-bold">${canal_comunicacion}</p>
        </div>
    </div>`;
    return resumeTemplate;
}

function showVoucher(filename){
    const contentTemplate = `<div class="text-center"><img src="./../../public/uploads/${filename}" class="img-fluid" alt=""></img></div>`;
    showInfoModal("Voucher", contentTemplate);
}

function showEditModal({recarga_id, client_id, monto, banco, canal_comunicacion}){
    $('#recarga_id_edit').val(recarga_id);
    $('#client_id_edit').val(client_id);    
    $('#montoAnterior_edit').val(monto);
    $('#montoRecargar_edit').val(monto);
    $('#banco_edit').val(banco);
    $('#canal_comunicacion_edit').val(canal_comunicacion);

    $("#corregirRecargaModal").modal("show");
}

function searchRecargaById(recarga_id) {
    const url = BASE_PATH + '/recarga/show';
    $.ajax({
        url: url,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ recarga_id }),
        success: function(data) {
            const dataToEdit = {
                recarga_id: data.data.id,
                client_id: data.data.cliente_id,
                monto: data.data.monto,
                banco: data.data.banco,
                canal_comunicacion: data.data.canal_comunicacion
            };
            showEditModal(dataToEdit);

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
        }
    });
}