<script setup>
import { ref } from 'vue';
import { useTicketStore} from '@/Stores/TicketStore';
import { useRouter } from 'vue-router';


</script>



<style>
  @import '../../css/style.css';
</style>

<<template>
   <Vueform
          :columns="{ container: 12, wrapper: 12 }"
          :display-errors="false"
          :endpoint="false"
          @submit="submit"
        >

         <TextElement
            name="fanshawe_id"
            label="Student-Id"
            :rules="['required', 'text', 'max:255']"
            field-name="fanshawe_id"
          />
          

          <TextElement
            name="email"
            label="Student-Email"
            :rules="['required', 'email', 'max:255']"
            field-name="email"
          />

          <TextElement
            name="first_name"
            label="First Name"
            input-type="text"
            :rules="['required']"
          />
          <TextElement
            name="last_name"
            label="Last Name"
            input-type="text"
            :rules="['required']"
          />
          <SelectElement
           name="programs"
           label="Program*"
          :native="false"
          :items="[
          'Business and Information system',
           'ITI',
             ]"
             :rule="['required']"
            />
            
            <TextElement
            name="program_level"
            label="Program Level*"
            input-type="text"
            :rules="['required']"
            />

            <SelectElement
           name="type_of_machines"
           label="Type of Machine*"
          :native="false"
          :items="[
            'Windows',
            'Mac',
             ]"
             :rule="['required']"
            />
            
            <SelectElement
           name="operating_systems"
           label="Operating System*"
          :native="false"
          :items="[
            'Windows 9',
           'Windows 10',
           'Windows 11',
             ]"
             :rule="['required']"
            />

            <SelectElement
           name="priority_level"
           label="Priority Level"
          :native="false"
          :items="[
            'Low',
           'Medium',
           ' High',
             ]"
             :rule="['required']"
            />
             
              <TextElement
            name="description"
            label="Description"
            :rules="['required', 'Message', 'max:255']"
            field-name="description"
          />
            

             <SelectElement
           name="courses"
           label="Course"
          :native="false"
          :items="[
            'Business intellegence and Big Data',
           'Software development',
           ' micro controller',
             ]"
             :rule="['required']"
            />

                <SelectElement
           name="assigned_tutor"
           label="Tutor"
          :native="false"
          :items="[
            '',
           '',
           ' ',
             ]"
             :rule="['required']"
            />
               <DatesElement name="dates" />


          <ButtonElement name="button" danger submits>Confirm Appoitment</ButtonElement>
        </Vueform>
</template>


<!--<script>
  export default {
    data(){
    return{
      fanshawe_id:'',
      email:'',
      first_name:'',
      last_name:'',
      programs:'',
      program_level:'',
      type_of_machines:'',
      operating_systems:'',
      priority:'',
      description:'',
      courses:'',
      assigned_tutor:'',
      scheduled_start_time:'',
      scheduled_end_time:''
    }
    },
  
  methods:{
    async submitticket()
    {
      let result = await axios.post('/api/tickets',{
       fanshawe_id:this.fanshawe_id,
       email:this.email,
       first_name:this.first_name,
       last_name:this.last_name,
       programs:this.programs,
       program_level:this.program_level,
       type_of_machines:this.type_of_machines,
       operating_systems:this.operating_systems,
       priority_level:this.priority_level,
       description:this.description,
       Courses:this.courses,
       assigned_tutor:this.assigned_tutor,
       scheduled_start_time:this.start_time,
       scheduled_end_time:this.end_time});

       console.warn(result);
       
    }
  }
  }
   


</script>-->