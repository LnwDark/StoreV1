window.addEventListener("offline", function () {
    $('#app').hide();
    $("#message").html('WARNING: Internet connection has been lost.').show();
});
window.addEventListener("online", function () {
    $("#message").empty().hide();
});

Vue.component('v-select', VueSelect.VueSelect);
let app = new Vue({
    el: '#app',
    data: {
        URL_SERVE: 'http://139.59.240.55',
        select: '',
        dataListProduct: [],
        dataHistory: [],
        Product: [],
        amount: '',
        dataBarcode: [],
        search:'',
        search_date:''

    },
    created() {
        this.getListData();
        this.getHistory()
    },
    computed: {
        productDetails() {
            let id = this.select.id;
            if (id != undefined) {
                return this.dataListProduct.filter(item => item.id === id)
            }
        },
      
        filterProduct(){  
            var search_data = this.dataHistory;
             searchString = this.search;
              searchDate = this.search_date;
                 if(!searchString && !searchDate){
                     return search_data.filter((item)=>{
                        return item.print
                     });
                 } 
                 searchString = searchString.trim().toLowerCase();
                  search_data = search_data.filter(item => {
                      if ((!searchString || item.product_name.toLowerCase().indexOf(searchString) !== -1 ) 
                        || item.keep_area_2.toLowerCase().indexOf(searchString)!== -1 
                         && (!searchDate || item.created_at === searchDate)
                      ) return item;
                 });
                 return search_data.filter((item)=>{
                    return item.print
                 })
             }
        
    },
    methods: {
        check(item){
            if(item.updated_at !=null){
                console.log(2)
                return 'success'
            }else{
                return '';
            }
        },
        hidePrint(model){
           return this.filterProduct.filter((item)=>{
                if(item.id === model.id){
                    item.print = false
                    console.log(item)
                }
            
            })
        },
        getListData() {
            let vm = this;
            axios.get(`${vm.URL_SERVE}/pcb/api/list-product`).then(function (response) {
                if (response.status === 200) {
                    vm.dataListProduct = response.data
                }
                console.log(response);
            })
        },

        SaveProduct() {
            let vm = this;
            let dataJson = {
                'product_id': vm.select.id,
                'amount': vm.amount
            }
            axios.post(`${vm.URL_SERVE}/pcb/api/save-import`, {
                data: dataJson
            }).then(function (response) {
                if (response.status === 200) {
                    vm.dataBarcode = response.data
                    console.log(response);
                    vm.amount = '';
                    $('#printBarcode').modal('show')
                }

            })
        },
        cancel() {
            location.href="index.php";
            location.reload();
        },
        print() {
            let vm = this;
            let barcode = vm.dataBarcode.barcode;
            let lct = vm.dataBarcode.locations;
            let amount = vm.dataBarcode.amount;
            let created_at = vm.dataBarcode.created_at;
            let part_name = vm.dataBarcode.part_name;
          
            axios.get('test.php?id=' + barcode + '&lct=' + lct+'&amount='+amount+'&created_at='+created_at+'&part_name='+part_name).then(function (response) {
                console.log(response);
            })
        },
        fetchData(){
            let vm = this;
            
            axios.get(`${vm.URL_SERVE}/import-store/history`).then(function (response) {
                if (response.status === 200) vm.dataHistory = response.data;
            });
            location.href='index.php';
           
        },
        reprint(model){
            console.log(model);
            let vm = this;
            let barcode = model.barcode;
            let lct = model.keep_area_2;
            let amount = model.amount;
            let created_at = model.created_at;
            let part_name = model.product_name;
            let count_lote = model.count_lote;
           // vm.cancel();
           let URL ='index.php?id='+ barcode + '&lct=' + lct+'&amount='+amount+'&created_at='+created_at+'&part_name='+part_name+'&count_lote='+count_lote
            location.href=URL
            // axios.get('index.php?id=' + barcode + '&lct=' + lct+'&amount='+amount+'&created_at='+created_at+'&part_name='+part_name).then(function (response) {
            //     console.log(response);
            // });
            axios.get(`${vm.URL_SERVE}/pcb/api/update-reprint?id=`+model.id).then(function (response) {
                if (response.status === 200) {
                    console.log(response);
                }
            })
        },
        getHistory() {
            let vm = this;

            axios.get(`${vm.URL_SERVE}/pcb/api/history`).then(function (response) {
                if (response.status === 200) vm.dataHistory = response.data
            })
            
           
        },
        remove(item) {
            let vm = this;
            var con = confirm('ต้องการลบข้อมูล ใช่หรือไม่ ?.');
            if (con) {
                axios.get(`${vm.URL_SERVE}/pcb/api/delete-history?id=`+item.id).then(function (response) {
                    if (response.status === 200) vm.getHistory()
                })
            }
        }
    }
});
$(document).keyup(function(e) {
    if (e.keyCode === 27) app.cancel() // esc
});