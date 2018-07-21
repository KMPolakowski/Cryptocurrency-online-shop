<template>
<div class="container" align="center">
  <div class="buy-input">
  
  
    <h2>Sell Cryptocurrencies</h2>

    <div class="input-group mb-2">

  <select class="custom-select" id="inputGroupSelect01" v-model="transaction.selectedCrypto" placholder="cryptocurrency">
    <!-- <option selected>Choose...</option> -->
    <option v-for="cryptoName in cryptoNames" v-bind:key="cryptoName" :value="cryptoName"> {{ cryptoName[0] }} </option>

  </select>




  
    </div>
  

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
        <input type="text" class="form-control" v-model="newAmount" placeholder="amount" >
        
   


    </div>
    

  <a class="btn btn-danger" @click="sell()"> Sell </a>
  
  

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
      }
    };
  },

  created() {
    this.fetchCryptoNames();
  },

  methods: {
    fetchCryptoNames() {
      fetch("buy/crypto_names")
        .then(res => res.json())
        .then(res => {
          this.cryptoNames = res;
        })
        .catch(err => console.log(err));
    },
    sell() {
      this.$http.post("sell", this.transaction).then(function(data) {
        console.log(data.body);
        if (isNaN(data.body)) {
          window.location.href = data.body;
        } else if (data.body == 0) {
          window.alert("Not enough cryptocurrency in your wallet.");
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
            this.transaction.selectedCrypto[1] = this.round(data.body);
            this.sell();
          }
        }
      });
    },
    round(amount) {
      return Math.round(amount * 100) / 100;
    }
  },

  computed: {
    newAmount: function() {
      if (
        isNaN(this.transaction.quantity) ||
        typeof this.transaction.selectedCrypto[0] !== "string"
      ) {
        return "";
      } else {
        return this.round(
          this.transaction.quantity * this.transaction.selectedCrypto[1]
        );
      }
    }
  }
};
</script>
