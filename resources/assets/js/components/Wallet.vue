<template>
  <div class="container" align="center">
    <div class="card">
        <div class="card-header">Wallet</div>

        <div class="card-body">


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Cryptocurrency</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Current Market Value in EUR </th>

                    </tr>
                </thead>
                <tbody>


                    <tr v-for="(item, key) in walletData" v-bind:key="key">
                        <th scope="row"> {{ key }} </th>
                        <td> {{ item[1] }} </td>
                        <td>{{ round(item[1] * item[0]) }} </td>
                    </tr>

                </tbody>

            </table>




        </div>

</div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      walletData: {}
    };
  },
  created() {
    this.fetchData();
  },

  methods: {
    fetchData() {
      this.$http.get("data/wallet").then(function(data) {
        // for (let i = 1; i < 101; i++) {
        //   console.log(data.body.data[i]);
        // }

        this.walletData = data.body;
        console.log(this.walletData);
        // Object.keys(data.body.data).forEach(function(key, index) {
        //   console.log(this[key].rank);
        // }, data.body.data);
      });
    },
    round(amount) {
      return Math.round(amount * 100) / 100;
    }
  },
  computed: {
    orderedCryptoData: function() {
      return _.orderBy(this.cryptoData, "rank");
    }
  }
};
</script>
