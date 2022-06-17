<template>
  <vs-card no-shadow>
    <!-- Info -->
    <vs-input class="w-full mb-base" label-placeholder="Name" v-model="name"></vs-input>
    <vs-input class="w-full mb-base" label-placeholder="Website" v-model="website"></vs-input>

    <!-- Save & Reset Button -->
    <div class="flex flex-wrap items-center justify-end">
      <vs-button class="ml-auto mt-2" @click="updateProjectGeneralData">Save Changes</vs-button>
      <vs-button class="ml-4 mt-2" type="border" color="warning">Reset</vs-button>
    </div>
  </vs-card>
</template>

<script>
import {http} from '@/services/requests'

export default {
  data () {
    return {
      name: '',
      website: ''
    }
  },
  computed: {
    activeUserInfo () {
      return this.$store.state.AppActiveUser
    },

    currentUser () {
      return authentication.currentUser.source._value
    }
  },

  methods:{
    updateProjectGeneralData() {
      http.put('projects/update/'+localStorage.getItem('selectedProjectId'),
        {
          name:this.name,
          website:this.website
        }).then(response => {
          this.$vs.notify({
            text:'Update',
            color:'success',
            iconPack: 'feather',
            icon:'icon-check'
          })
      }).catch(error => {

      });
    },

    getProject() {
      http.get('projects/'+localStorage.getItem('selectedProjectId')).then(response => {
        this.name = response.data.name;
        this.website = response.data.website;
      }).catch(error => {

      });
    }
  },

  created () {
    this.getProject();
  },

  mounted () {

  }
}
</script>
