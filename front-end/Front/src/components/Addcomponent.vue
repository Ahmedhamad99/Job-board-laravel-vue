<template>

 <div>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addingmodal">Add Student</button>
    
    <div class="modal fade" id="addingmodal" data-bs-backdrop="static" data-bs-keyboard=false>
       
        <div class="modal-dialog modal-dialog-centerd">
            <div class="modal-content">
                <div class="class modal-header"><h4 class="text-center w-100 modal-header">Adding</h4></div>
                <div class="modal-body">
                    <input type="text" class="form-control my-2" placeholder="Name" v-model="studname">
                    <input type="text" class="form-control my-2" placeholder="City" v-model="studcity">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal" @click="addstudent">Add</button>
                    <button class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
 </div>
</template>


<script setup>
import {ref,reactive,defineEmits} from 'vue';
let studname = ref('');
let studcity = ref('');
let newstudent = reactive({});
let emit = defineEmits(["addnewstudent"])
async function addstudent()
{
    newstudent = {name:studname.value,city:studcity.value};
    emit('addnewstudent',newstudent)
}
</script>