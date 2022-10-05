<div class="modal" id="{{ $idModal }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Fecha Oferta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" id="form-fecha">
                @csrf
                <input type="hidden" name="id" value="{{ $producto->id }}">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Fecha termino oferta</label>
                    <input type="date" class="form-control" name="date" value="{{ $hoy }}" min="{{ $hoy }}">
                  </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="input" class="btn btn-primary nuevo-servicio" form="form-fecha">
                Agregar Fecha
                </button>
        </div>
      </div>
    </div>
  </div>


