<template>
    <div v-for="svg in svgs">
        <div v-if="svg.id == selected" @change="selected">
            {{ svg }}
        </div>
    </div>
    <q-select v-model="selected" :options="options" label="Standard" style="width: 250px" />
</template>
<script>
import { api } from 'boot/axios'
import { LocalStorage } from 'quasar'

export default {

    name:'RestSalesPage',
    data() {

        return {

            restaurant : {},
            employee : {},
            svgs : {},
            selected : '1',
            options : []

        }

    },
    beforeCreate(){

        let local_arr = LocalStorage.getAll();
        let rest_id = local_arr.restaurant_id;
        let token = local_arr.token;

       api.get('/designs?filter[restaurant_id]=' + rest_id,{
            headers: {
                Authorization: `Bearer ${token}`
            }
       }).then(async (response) => {

            this.svgs = await response.data.data
            this.setOptions()

       })

    },
    methods : {

        setOptions(){

            for(let i = 0;i < this.svgs.length;i++){

                this.options.push(this.svgs[i].id)

            }

        },
        setSvg(){

            console.log(this.selected);

        }

    }
}
</script>
<style>

</style>