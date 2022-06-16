<template>
  <vs-tabs :position="isSmallerScreen ? 'top' : 'left'" class="tabs-shadow-none" id="profile-tabs" :key="isSmallerScreen">
    <!-- GENERAL -->
    <vs-tab icon-pack="feather" icon="icon-user" :label="!isSmallerScreen ? 'General' : ''">
      <div class="tab-general md:ml-4 md:mt-0 mt-4 ml-0">
        <project-settings-general/>
      </div>
    </vs-tab>
    <vs-tab icon-pack="feather" icon="icon-info" :label="!isSmallerScreen ? 'Data settings' : ''">
      <div class="tab-info md:ml-4 md:mt-0 mt-4 ml-0">
        <project-settings-info/>
      </div>
    </vs-tab>
    <vs-tab icon-pack="feather" icon="icon-link-2" :label="!isSmallerScreen ? 'Product Import' : ''">
      <div class="tab-text md:ml-4 md:mt-0 mt-4 ml-0">
        <product-import/>
      </div>
    </vs-tab>
  </vs-tabs>
</template>

<script>
import ProjectSettingsGeneral from './ProjectSettingsGeneral.vue'
import ProjectSettingsInfo from './ProjectSettingsInfo.vue'
import ProductImport from './ProductImport.vue'
import axios from 'axios'

export default {
  components: {
    ProjectSettingsGeneral,
    ProjectSettingsInfo,
    ProductImport,
  },
  data () {
    return {
      project:{},
    }
  },
  computed: {
    isSmallerScreen () {
      return this.$store.state.windowWidth < 768
    }
  },

  methods:{
    getProject() {
      axios.get('projects/'+localStorage.getItem('selectedProjectId')).then(response => {
        console.log(response.data, 12212)
        this.project = response.data
      }).catch(error => {

      });
    }
  },

  mounted () {
    this.getProject()
  }
}
</script>

<style lang="scss">
#profile-tabs {
  .vs-tabs--content {
    padding: 0;
  }
}
</style>
