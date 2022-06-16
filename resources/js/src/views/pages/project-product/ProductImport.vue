<template>
  
</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      old_password: '',
      new_password: '',
      confirm_new_password: '',
      popupActive: false,
      name: '',
      xml_file_url: '',
      projectFields: {},
      xmlFields: {},
      http: 'http',
      authentication: 'no_authentication',
      encoding: 0,
      activeTab: 'setting',
      no_action: 'no_action',
      title: true,
      description: true,
      image: true,
      productMappings: {
        importField: '',
        internalChannableField: '',
        isCustom: false
      }
    }
  },
  computed: {
    activeUserInfo () {
      return this.$store.state.AppActiveUser
    },

    validateForm () {
      return !this.errors.any() && this.name !== '' && this.xml_file_url !== '' && this.http !== '' && this.authentication !== '' && this.encoding !== ''
    }
  },

  methods:{
    continueXml() {

      this.$vs.loading({
        background: this.backgroundLoading,
        color: this.colorLoading,
        container: '#button-with-loading',
        scale: 0.45
      })

      if (this.validateForm) {
        axios.post('xml-import', {name:this.name, xml_file_url:this.xml_file_url}).then(response => {
          this.projectFields = response.data
          this.activeTab = 'mapping';
          this.$vs.loading.close('#button-with-loading > .con-vs-loading')
        }).catch(error => {
          this.$vs.loading.close('#button-with-loading > .con-vs-loading')
        });
      }
    },

    tab(tab) {
      this.activeTab = tab;
    },

    saveImport() {
      console.log(this.productMappings)
      this.activeTab = 'safety';
    },

    existCustom(value) {
      var obj = this.xmlFields.filter(function(elem){
        if(elem.field_title == value) return elem.field_title;
      })
      if(obj.length > 0) {
        return true;
      } else {
        return false;
      }
    },

    getXmlFields() {
      axios.get('xml-fields', {name:this.name, xml_file_url:this.xml_file_url}).then(response => {
        this.xmlFields = response.data
      }).catch(error => {

      });
    },

    save() {
      this.$vs.loading({
        background: this.backgroundLoading,
        color: this.colorLoading,
        container: '#loading-save',
        scale: 0.45
      })


      axios.post('save-import-data',
        {
          name: this.name,
          http: this.http,
          authentication: this.authentication,
          encoding: this.encoding,
          xml_file_url: this.xml_file_url,
          no_action: this.no_action,
          projectFields: this.projectFields,
          import_type: 'XML',
          id_project: 1,
          title: this.title,
          image: this.image,
          description: this.description,
        }
        ).then(response => {
        // this.xmlFields = response.data
        this.$vs.loading.close('#loading-save > .con-vs-loading')
      }).catch(error => {
        this.$vs.loading.close('#loading-save > .con-vs-loading')
      });
    }
  },

  mounted () {
    this.getXmlFields();
  }
}
</script>

<style>
.card-img-top {
  width: 40px;
  height: 40px;
}

.arrow-sign {
  font-size: 25px;
}

.form-control {
  height: 42px;
}

.delete-button{
  font-size: 24px;
  color: red;
}

/* Tabs*/
section {
  padding: 60px 0;
}

section .section-title {
  text-align: center;
  color: #007b5e;
  margin-bottom: 50px;
  text-transform: uppercase;
}

.nav-item{
  color: #303A73 !important;
  cursor: pointer;
}


#tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
  color: #f3f3f3;
  background-color: transparent;
  border-color: transparent transparent #f3f3f3;
  border-bottom: 4px solid !important;
  font-size: 20px;
  font-weight: bold;
}
#tabs .nav-tabs .nav-link {
  border: 1px solid transparent;
  border-top-left-radius: .25rem;
  border-top-right-radius: .25rem;
  color: #eee;
  font-size: 20px;
}
</style>
