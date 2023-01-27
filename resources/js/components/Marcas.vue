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
          <table-component 
          :dados="marcas"
          :titulos="['id', 'nome', 'imagem']"
          ></table-component>
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
    <template v-slot:alertas>
      <alert-component
        tipo="success"
        :detalhes="transacaoDetalhes"
        titulo="Cadastro realizado com sucesso!"
        v-if="transacaoStatus == 'adicionado'"
      ></alert-component>
      <alert-component
        tipo="danger"
        :detalhes="transacaoDetalhes"
        titulo="Erro ao tentar cadastrar a marca."
        v-if="transacaoStatus == 'erro'"
      ></alert-component>
    </template>

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
            v-model="nomeMarca"
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
            @change="carregarImagem($event)"
          />
        </input-container-component>
      </div>
    </template>
    <template v-slot:rodape>
      <slot name="conteudo">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button type="button" class="btn btn-secondary" @click="salvar()">
          Salvar
        </button>
      </slot>
    </template>
  </modal-component>
</template>

<script>
export default {
  computed: {
    token() {
      let token = document.cookie;
      token = token
        .split("; ")
        .find((row) => row.startsWith("access_token="))
        .split("=")[1];
      return "Bearer " + token;
    },
  },
  data() {
    return {
      urlBase: "http://localhost:8000/api/v1/marca",
      nomeMarca: "",
      arquivoImagem: [],
      transacaoStatus: "",
      transacaoDetalhes: {},
      marcas: [],
    };
  },
  methods: {
    carregarLista() {
      let config = {
        headers: {
          'Accept': 'application/json',
          'Authorization': this.token
        }
      }

      axios
        .get(this.urlBase, config)
        .then((response) => {
          this.marcas = response.data;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    carregarImagem(e) {
      this.arquivoImagem = e.target.files;
    },
    salvar() {
      console.log(this.nomeMarca, this.arquivoImagem[0]);

      let formData = new FormData();
      formData.append("nome", this.nomeMarca);
      formData.append("imagem", this.arquivoImagem[0]);

      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      axios
        .post(this.urlBase, formData, config)
        .then((response) => {
          (this.transacaoStatus = "adicionado"),
            (this.transacaoDetalhes = {
              mensagem: "ID do registro: " + response.data.id,
            });
        })
        .catch((errors) => {
          (this.transacaoStatus = "erro"),
            (this.transacaoDetalhes = {
              mensagem: errors.response.data.message,
              dados: errors.response.data.errors,
            });
        });
    },
  },
  mounted() {
    this.carregarLista();
  },
};
</script>
