
<div class="row mt-3">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card p-4">
            <h2 class="card_title">Consulta de cliente</h2>
            <form action="#" id="searchClientForm">
                <div class="form-group">
                    <label for="">Buscar cliente</label>
                    <div class="d-flex gap-2">
                        <input type="text" name="playerid" id="playerid"  class="form-input" placeholder="PlayerID" required />
                        <button class="btn-brand" id="btnBuscar">Buscar</button>
                    </div>
                </div>
            </form>
        </div>

        <div id="searchResult"></div>

    </div>
    <div class="col-12 col-md-6 col-lg-8">
        <div class="card">
            <div class="card-header">Historial de recargas</div>
            <div class="card-body">
                <div class="table-responsive historial-table">
                    <table class="table table-sm table-striped" id="historialTable">
                        <thead>
                            <tr>
                                <th>Monto</th>
                                <th>Banco</th>
                                <th>Canal</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="historialTableBody">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    
</div>

<!-- Modal -->
<div class="modal fade" id="recargarSaldoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Recargar Saldo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="recargarSaldoForm" enctype="multipart/form-data">
                <div class="modal-body">

                    <input type="hidden" id="client_id" name="client_id" required />
                    
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="montoRecargar" id="montoRecargar" step="0.01" placeholder="Monto" required/>
                        <label for="montoRecargar">Monto</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="banco" name="banco" aria-label="Banco" required>
                            <option selected disabled value="">Seleccione</option>
                            <option value="Interbank">Interbank</option>
                            <option value="BCP">BCP</option>
                            <option value="BBVA">BBVA</option>
                            <option value="Banco de la Nacion">Banco de la Nación</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <label for="banco">Banco</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="canal_comunicacion" name="canal_comunicacion" aria-label="Canal de Comunicación" required>
                            <option value="WhatsApp">WhatsApp</option>
                            <option value="Telegram">Telegram</option>
                            <option value="FB Messenger">FB Messenger</option>
                        </select>
                        <label for="canal_comunicacion">Canal de comunicación</label>
                    </div>

                    <div class="form-group mb-3">
                        <label for="voucherImg">Voucher</label>
                        <input type="file" class="form-control" name="voucherImg" id="voucherImg"  accept="image/*"required/>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnRecargar" >Recargar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal edit Recarga -->
<div class="modal fade" id="corregirRecargaModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Corregir recarga</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="corregirRecargaForm">
                <div class="modal-body">

                    <input type="hidden" id="recarga_id_edit" name="recarga_id_edit" required />
                    <input type="hidden" id="client_id_edit" name="client_id_edit" required />
                    <input type="hidden" id="montoAnterior_edit" name="montoAnterior_edit" required />
                    
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="montoRecargar_edit" id="montoRecargar_edit" step="0.01" placeholder="Monto" required/>
                        <label for="montoRecargar_edit">Monto</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="banco_edit" name="banco_edit" aria-label="Banco" required>
                            <option value="Interbank">Interbank</option>
                            <option value="BCP">BCP</option>
                            <option value="BBVA">BBVA</option>
                            <option value="Banco de la Nacion">Banco de la Nación</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <label for="banco_edit">Banco</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="canal_comunicacion_edit" name="canal_comunicacion_edit" aria-label="Canal de Comunicación" required>
                            <option value="WhatsApp">WhatsApp</option>
                            <option value="Telegram">Telegram</option>
                            <option value="FB Messenger">FB Messenger</option>
                        </select>
                        <label for="canal_comunicacion_edit">Canal de comunicación</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnEditRecarga">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
