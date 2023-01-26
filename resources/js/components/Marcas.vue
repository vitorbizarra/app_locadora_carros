<template>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <card-component titulo="Busca de Marcas" class="mb-4">
        <template v-slot:conteudo>
          <div class="row">
            <div class="col mb-3">
              <input-container-component
                titulo="ID"
                id="inputId"
                id-help="idHelp"
                texto-ajuda="Opcional. Informe o ID do registro"
              >
                <input
                  type="number"
                  class="form-control"
                  id="inputId"
                  aria-describedby="nomeHelp"
                  placeholder="ID"
                />
              </input-container-component>
            </div>

            <div class="col mb-3">
              <input-container-component
                titulo="Nome"
                id="inputNome"
                id-help="nomeHelp"
                texto-ajuda="Opcional. Informe o nome da marca"
              >
                <input
                  type="text"
                  class="form-control"
                  id="inputNome"
                  aria-describedby="nomeHelp"
                  placeholder="Nome"
                />
              </input-container-component>
            </div>
          </div>
        </template>

        <template v-slot:rodape>
          <div class="w-100 d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
              Pesquisar
            </button>
          </div>
        </template>
      </card-component>

      <card-component titulo="Relação de marcas">
        <template v-slot:conteudo>
          <table-component></table-component>
        </template>

        <template v-slot:rodape>
          <div class="w-100 d-flex">
            <button
              type="button"
              class="btn btn-primary btn-sm ms-auto"
              data-bs-toggle="modal"
              data-bs-target="#modalMarca"
            >
              Adicionar
            </button>
          </div>
        </template>
      </card-component>
    </div>
  </div>
  <modal-component id="modalMarca" titulo="Adicionar marca">
    <template v-slot:conteudo>
      <div class="form-group">
        <input-container-component
          titulo="Nome"
          id="novoNome"
          id-help="novoNomeHelp"
          texto-ajuda="Opcional. Informe o nome da marca"
        >
          <input
            type="text"
            class="form-control"
            id="novoNome"
            aria-describedby="novoNomeHelp"
            placeholder="Nome"
          />
        </input-container-component>

        <input-container-component
          titulo="Imagem"
          id="novoImagem"
          id-help="novoImagemHelp"
          texto-ajuda="Selecione uma imagem no formato PNG"
        >
          <input
            type="file"
            class="form-control"
            id="novoImagem"
            aria-describedby="novoImagemHelp"
          />
        </input-container-component>
      </div>
    </template>
    <template v-slot:rodape>
      <slot name="conteudo"></slot>
    </template>
  </modal-component>
</template>

