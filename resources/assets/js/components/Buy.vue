<template>

<div class="container" align="center">
  <div class="buy-input">
  
  
    <h2>Buy Cryptocurrencies</h2>

    <div class="input-group mb-2">
  

  <div class="input-group-prepend">
  </div>
        <input type="text" class="form-control"  v-model="searchInput" placeholder="Search for coins">

        
</div>

<ul class="list-group mb-2 searchResult">

  <li class="list-group-item" @click="getCoin(result)" :class="{active: result[0] == selectedCoinId}" v-for="result in searchResults" v-bind:key="result" v-if="typeof result !== 'undefined'"> {{result[1] }}</li>

</ul>

    <div class="input-group mb-2">
  

  
  <!-- Freaking ugly -->
  <div class="input-group-prepend">
    <span class="input-group-text">Quantity</span>
  </div>

        <input type="text" class="form-control" v-model="transaction.quantity" placeholder="quantity">
</div>
  <div class="input-group mb-2">

<div class="input-group-prepend">
    <span class="input-group-text">Amount in EUR</span>
  </div>
        <div class="d-inline-flex p-2 bd-highlight"> {{ newAmount }} </div>
        
   


    </div>
    

  <a class="btn btn-danger" @click="pay()"> Pay with PayPal or Card</a>
  
  

  </div>

</div>
</template>

<script>
export default {
  data() {
    return {
      cryptoNames: [],
      transaction: {
        selectedCrypto: {},
        quantity: null
      },
      listings: {},
      searchInput: null,
      searchResults: [],
      selectedCoinId: null
    };
  },
  created() {
    this.getListings();
  },

  mounted() {
    $("#background").height(0);

    let scrollHeight = Math.max(
      document.body.scrollHeight,
      document.documentElement.scrollHeight,
      document.body.offsetHeight,
      document.documentElement.offsetHeight,
      document.body.clientHeight,
      document.documentElement.clientHeight
    );

    $("#background").height(scrollHeight);
    window.dispatchEvent(new Event("resize"));
  },

  methods: {
    pay() {
      if (
        typeof this.transaction.selectedCrypto !== "undefined" &&
        this.transaction.quantity !== null &&
        this.transaction.quantity > 0
      ) {
        this.$http.post("buy/pay", this.transaction).then(function(data) {
          if (isNaN(data.body)) {
            // window.console.log(data.body);
            window.location.href = data.body;
          } else {
            if (
              confirm(
                "The prices just changed. New Price: " +
                  this.round(data.body) +
                  "| New Amout: " +
                  this.round(data.body * this.transaction.quantity) +
                  "| Do you accept the transaction? "
              )
            ) {
              this.transaction.selectedCrypto.priceEUR = this.round(data.body);
              // console.log(this.transaction.selectedCrypto[1]);
              this.pay();
            }
          }
        });
      }
    },

    getCoin(coin) {
      window.console.log(coin[1]);
      this.selectedCoinId = coin[0];

      this.$http.get("api/coin_price/" + coin[0]).then(function(data) {
        this.transaction.selectedCrypto.id = coin[0];
        this.transaction.selectedCrypto.priceEUR = data.body[0];

        window.console.log(this.transaction.selectedCrypto.priceEUR);
      });
    },

    getListings() {
      this.$http.get("api/internal_listings").then(function(data) {
        this.listings = data.body;
        window.console.log(this.listings);
      });
    },

    onSearchInput() {
      // window.console.log();
    },

    round(amount) {
      return Math.round(amount * 100) / 100;
    }
  },

  computed: {
    newAmount: function() {
      if (
        isNaN(this.transaction.quantity) ||
        typeof this.transaction.selectedCrypto == "null"
      ) {
        return "";
      } else {
        if (this.transaction.quantity > 0) {
          return this.round(
            this.transaction.quantity * this.transaction.selectedCrypto.priceEUR
          );
        }
      }
    }
  },

  watch: {
    searchInput: function(val) {
      let result = [];
      let i = 0;
      this.listings.forEach(function(listing) {
        if (listing[1].toUpperCase().includes(val.toUpperCase()) && i < 10) {
          result[i] = listing;
          i++;
        }
      });
      window.console.log(result);
      this.searchResults = result;
    }
  }
};
</script>
