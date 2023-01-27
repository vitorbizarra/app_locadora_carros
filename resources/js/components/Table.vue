<template>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col" v-for="t in titulos" :key="t">{{ t }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="obj in dados" :key="obj.id">
        <td :v-if="titulos.includes(chave)" v-for="valor, chave in obj" :key="chave">
          {{valor}}
        </td>
        <!-- <th scope="row">{{ m.id }}</th>
        <td>{{ m.nome }}</td>
        <td>
          <img
            :src="'/storage/' + m.imagem"
            :alt="m.nome"
            width="50"
            height="50"
          />
        </td> -->
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["dados", "titulos"],
};
</script>
