<script setup></script>

<style></style>
<template>
  <div class="">
    <nav class="bg-slate-100 flex justify-end py-4 items-center px-8 gap-4">
      <div class="flex items-center">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="w-8 h-7"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
          />
        </svg>
        <div class="text-red-700 font-bold">LabSquad</div>
      </div>
      <router-link class="inline-flex gap-2 items-center" to="/login">
        <button
          class="bg-blue-950 hover:bg-blue-950/80 text-white text-sm font-bold py-2 px-6 rounded-lg"
        >
          Log In
        </button>
      </router-link>
    </nav>
    <main class="flex flex-col gap-16">
      <section class="flex w-full justify-between gap-8 max-w-7xl mx-auto my-4">
        <div class="flex flex-col gap-8 justify-center p-8">
          <img
            class="max-w-md max-h-lg mx-auto"
            src="../../images/logo.png"
            alt="FanSquadServe Logo"
          />
          <div class="text-center">
            <p class="text-red-700 font-bold text-3xl">
              Your go-to booking solution for
            </p>
            <p class="text-red-700 font-bold text-3xl">
              all your
              <a class="text-black">LabSquad</a> needs.
            </p>
          </div>
          <router-link
            class="bg-red-700 text-white font-bold py-2 px-4 rounded-lg self-center"
            to="/service-request"
          >
            Book Appointment
          </router-link>
        </div>
        <img class="max-w-xl" src="../../images/1.png" alt="Image" />
      </section>
      <section class="flex flex-col bg-slate-50 py-16 gap-16">
        <p class="text-red-700 font-bold text-4xl text-center">
          We are the LabSquad!
        </p>
        <div
          class="flex max-w-7xl mx-auto items-center justify-center gap-16 w-full"
        >
          <p class="w-96 leading-7">
            We are a small group of IT students here at London South Campus,
            dedicated to helping our fellow students for any inquiries related
            to IT courses or academic technical matters.
          </p>
          <img class="max-w-xl" src="../../images/2.jpg" alt="Fanshawe London South Campus" />
        </div>
      </section>
      <section class="flex flex-col py-16 gap-16">
        <p class="text-red-700 font-bold text-4xl text-center">Meet Our Team</p>
        <div class="flex justify-center gap-8">
          <div class="flex flex-col gap-4">
            <img class="h-40 w-40 border-2" src="../../images/Destiny.png" />
            <a class="text-red-700 font-bold text-center"> Destiny Akabuogu</a>
          </div>
          <div class="flex flex-col gap-4">
            <img class="h-40 w-40 border-2" src="../../images/Ari.png" />
            <a class="text-red-700 font-bold text-center">Arihanth Chilveri</a>
          </div>
          <div class="flex flex-col gap-4">
            <img class="h-40 w-40 border-2" src="../../images/Gaby.png" />
            <a class="text-red-700 font-bold text-center">Gabriella Mantilla</a>
          </div>
        </div>
      </section>
      <section class="flex flex-col py-16 gap-16 bg-slate-50">
        <div class="flex justify-center">
          <p class="text-red-700 font-bold text-4xl">Our Services</p>
        </div>
        <div class="flex justify-center gap-16 items-center">
          <div class="flex flex-col gap-8 w-96 leading-7">
            <h3 class="text-red-700 font-bold">Peer Tutoring</h3>
            <p>
              Seeking to improve your understanding or performance in your IT
              related courses? We've got you covered!
            </p>
            <p>
              The LabSquad team offers peer tutoring to help enhance your
              learning and confidence on your academic journey here at Fanshawe.
            </p>
          </div>
          <img class="max-w-md" src="../../images/3.png" alt="Peer Tutoring Image" />
        </div>
        <div class="flex justify-center gap-16 items-center">
          <img class="max-w-md" src="../../images/4.png" alt="Lab and Tech Support Image" />
          <div class="flex flex-col gap-8 w-96 leading-7">
            <h3 class="font-bold text-red-700">Lab and Tech Support</h3>
            <p>
              Experiencing technical issues with your laptop or software
              installation? We've got your back!
            </p>
            <p>
              The LabSquad team provides lab and tech support to assist you with
              academic software installation and trobleshooting activities.
            </p>
          </div>
        </div>
      </section>
      <section
        class="flex justify-between items-end p-8 pt-0 max-w-6xl mx-auto w-full"
      >
        <div class="flex flex-col gap-8">
          <img class="max-w-24" src="../../images/footer-logo.png" />
          <p class="text-sm w-96 leading-6">
            Join us at FanSquadServe, your dedicated tech support team at
            Fanshawe College's South Campus. With our expert assistance,
            personalized solutions, and commitment to efficiency, we're here to
            empower your tech journey. Say goodbye to tech troubles and hello to
            seamless productivity. Connect with FanSquadServe today and
            experience the difference!
          </p>
        </div>
        <div class="text-sm flex flex-col gap-2">
          <p class="ml-7">CONTACT US</p>
          <div class="flex gap-4 items-center">
            <font-awesome-icon icon="fa-home" size="md" class="text-red-500" />
            <div>
              <p>Location:</p>
              <p>2nd Floor, Room 204</p>
              <p>Fanshawe London South Campus</p>
            </div>
          </div>
          <div class="flex gap-4 items-center">
            <font-awesome-icon icon="fa-phone" size="md" class="text-red-500" />
            <div>
              <p>Office hours:</p>
              <p>Monday to Friday 9AM to 9PM</p>
              <p>By Appointment</p>
            </div>
          </div>
        </div>
      </section>
    </main>
    <footer class="bg-gray-900 text-white p-8">
      <p class="text-center">
        Copyright © 2024 Business & Information Systems Architecture Cohort
      </p>
    </footer>
  </div>
</template>
