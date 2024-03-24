<script setup>
</script>
<template>
      <div class="max-w-6xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-auto flex-grow" >
    <h1 class="text-amber-800 text-3xl font-bold flex-shrink-0">
      User Details
    </h1>
    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <h2 class="text-amber-800 text-lg font-bold">User Details</h2>
      <Vueform ref="formRef" @submit="submitTicket" :endpoint="false">
        <StaticElement name="id" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">User ID:</span>
          </div>
        </StaticElement>

        <StaticElement name="first_name" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">First Name:</span>
          </div>
        </StaticElement>

        <StaticElement name="last_name" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Last Name:</span>
           
          </div>
        </StaticElement>
        <StaticElement name="role" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Role:</span>
         </div>
        </StaticElement>

        <StaticElement name="email" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Email:</span>
         </div>
        </StaticElement>

        <StaticElement name="" class="col-span-4">
          <div class="text-sm">
            <span class="font-medium">Last LogIn Date :</span>
         </div>
        </StaticElement>
     
        <CheckboxElement name="is_active" class="col-span-4">
              Is Active
        </CheckboxElement>

        <ButtonElement
          name="button"
          button-class="font-semibold "
          danger
          submits
          class="mx-auto"
          >Update User</ButtonElement
        >
      </Vueform>
      
      </div>
    </div>
</template>    