<template>

    <div class ="container" id="dashboard-wrapper">


    <table class="table-striped w-100 p-3">
                <thead class="thead-light">
                    <tr>
                        <th scope="col"> #</th>
                        <th scope="col"> Name </th>

                        <th scope="col"> Market Cap </th>
                        <th scope="col"> Price </th>
                        <th scope="col"> Volume 24h </th>
                        <th scope="col"> Circulating Supply </th>
                        <th scope="col"> Change 24h </th>
                        <th scope="col"> Price Graph 7d </th>

                    </tr>
                </thead>
                <tbody>


                    <tr v-for="item in orderedCryptoData" v-bind:key="item.rank">
                        <th scope="row"> {{ item.rank}} </th>
                        <td> <img v-bind:src = "'https://s2.coinmarketcap.com/static/img/coins/16x16/' + item.id + '.png'"> {{item.name}} </td>
                        <td>{{item.quotes.EUR.market_cap}} </td>
                        <td>{{item.quotes.EUR.price}} </td>
                        <td>{{item.quotes.EUR.volume_24h}} </td>
                        <td>{{item.circulating_supply}} </td>
                        <td v-if="item.quotes.EUR.percent_change_24h >= 0" style="color: green">{{item.quotes.EUR.percent_change_24h}} % </td>
                        <td v-else style ="color: red">{{item.quotes.EUR.percent_change_24h}} % </td>
                        <td> <img v-bind:src= "'https://s2.coinmarketcap.com/generated/sparklines/web/7d/usd/' + item.id +'.png'"> </td>
                    </tr>


                </tbody>

    </table>

</div>
</template>

<script>
export default {
  data() {
    return {
      cryptoData: {}
    };
  },
  created() {
    this.fetchData();
  },

  methods: {
    fetchData() {
      this.$http.get("api/dashboard").then(function(data) {
        // for (let i = 1; i < 101; i++) {
        //   console.log(data.body.data[i]);
        // }

        this.cryptoData = data.body.data;
        // Object.keys(data.body.data).forEach(function(key, index) {
        //   console.log(this[key].rank);
        // }, data.body.data);
      });
    }
  },
  computed: {
    orderedCryptoData: function() {
      return _.orderBy(this.cryptoData, "rank");
    }
  }
};
</script>

