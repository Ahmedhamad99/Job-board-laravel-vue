<template>
    <div>
     <Addcomponent @addnewstudent="addstudent" class="justify-content-center;"/>
     <table class="table table-striped table-bordered">
         <thead>
             <tr>
                 <td>ID</td>
                 <td>Name</td>
                 <td>city</td>
                 <td>Action</td>
             </tr>
         </thead>
         <tbody>
             <tr v-for="(student) in students" :key="student.id">
                 <td>{{ student.id }}</td>
                 <td><router-link class=''  :to="'/studentd/'+student.id">{{ student.name }}</router-link></td>
                 <td>{{ student.city }}</td>
                 <td><button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updatemodal" @click="filldata(student)">update</button>
                 <button class="btn btn-danger" data-bs-toggle="modal" @click="deletstu(student.id)">Delete</button></td>
             </tr>
         </tbody>
         <tfoot>
            <tr>
                <td colspan="4" class="bg-dark text-light text-center">Total Number of Students: {{ students.length }}</td>
            </tr>
         </tfoot>
     </table>
    <div class="modal fade" id="updatemodal" data-bs-backdrop="static" data-bs-keyboard='false'>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header btn-warning">
                    <h4 class="text-center">Update</h4>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control my-2" v-model="stuid" disabled>
                    <input type="text" class="form-control my-2" v-model="stuname">
                    <input type="text" class="form-control my-2" v-model="stucity">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" data-bs-dismiss="modal" @click="update">Update</button>
                    <button class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div> 
    </div>
 </template>


<script setup>
import Addcomponent from './Addcomponent.vue';
import { ref,onMounted } from 'vue';
let students= ref([]);
let stucity=  ref('');
let stuname = ref('');
let stuid = ref('')
onMounted(async ()=>{
    let response = await fetch("http://localhost:5000/students");
    let jasonObject = await response.json();
    students.value = jasonObject;
});

async function deletstu(id)
{
  if(confirm("Are you shure?"))
  {
    await fetch(`http://localhost:5000/students/${id}`,{
    method:"DELETE"

   })
   students.value.splice(students.value.findIndex(std=>std.id==id),1);
  }
}
async function addstudent(data)
{ 
    data.id = (Math.max(...students.value.map(stud=>stud.id))+1).toString();
    await fetch("http://localhost:5000/students",
    {
        method:"POST",
        body:JSON.stringify(data)

    }
    );
    students.value.push(data);
}

function filldata(stdid)
{
   stucity.value = stdid.city;
   stuname.value = stdid.name;
   stuid.value =stdid.id; 
}

async function update()
{
    let dataupdat = {id:stuid.value,name:stuname.value,city:stucity.value};
  await fetch(`http://localhost:5000/students/${stuid.value}`,{
    method:"PUT",
    body:JSON.stringify(dataupdat)
  });
  students.value.find(std=>std.id==stuid.value).name=stuname.value;
  students.value.find(std=>std.id==stuid.value).city=stucity.value;

};
</script> 


<style scoped>

</style>