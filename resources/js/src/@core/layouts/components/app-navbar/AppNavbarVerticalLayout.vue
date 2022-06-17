<template>
  <div class="navbar-container d-flex content align-items-center">

    <!-- Nav Menu Toggler -->
    <ul class="nav navbar-nav d-xl-none">
      <li class="nav-item">
        <b-link
          class="nav-link"
          @click="toggleVerticalMenuActive"
        >
          <feather-icon
            icon="MenuIcon"
            size="21"
          />
        </b-link>
      </li>
    </ul>

    <!-- Left Col -->
    <div class="bookmark-wrapper align-items-center flex-grow-1 d-none d-lg-flex">

      <!-- Bookmarks Container -->
      <bookmarks />
    </div>
    <validation-provider
    #default="{ errors }"
    >

    <div class="px-6 pb-2 mr-5">
        <b-dropdown
          :text="selectedProject"
          block
          variant="primary"
          class="m-2 button-select-project"
          menu-class="w-100"
        >
          <form style="margin-top: -10px;">
            <div class="input-group mb-3">
              <input type="text" class="form-control"  @keyup="searchProject" placeholder="Search"
                 v-model="search"    aria-label="Recipient's username" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="input-group-text" @click="searchProject" type="button"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </form>
        <b-dropdown-item v-for="item in searchProject"   @click="selectedPro(item)"  :key="item.id" href="#" v-if="searchProject.length">
          {{ item.name }}
        </b-dropdown-item>
      </b-dropdown>
    </div>

    <div class="px-6 pb-2 mr-5">
      <vs-button @click="activePrompt = true" class="w-full">Add Project</vs-button>
      <vs-prompt
        title="Add Project"
        accept-text= "Add Project"
        button-cancel = "border"
        @cancel="clearFields"
        @accept="addProject"
        @close="clearFields"
        :is-valid="validateForm"
        :active.sync="activePrompt">
        <div>
          <form>
            <div class="vx-row">
              <div class="vx-col w-full">
                <vs-input
                  v-validate="'required'"
                  name="name"
                  class="w-full mb-4 mt-5"
                  placeholder="Name"
                  v-model="projects.name"
                  :color="validateForm ? 'success' : 'danger'"
                />
                <span class="text-danger text-sm">{{ errors[0] }}</span>
                <vs-input
                  @input="checkDomain"
                  v-validate="{ required: true, regex: /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/ }"
                  name="website"
                  class="w-full mb-4 mt-5"
                  placeholder="Website"
                  v-model="projects.website"
                  :color="validateForm ? 'success' : 'danger'" />
                <span class="text-danger text-sm">{{ errors[0] }}</span>

                <v-select @input="chooseCountry($event)"  placeholder="Country" name="country"  label="country_name"
                          class="w-full mb-4 mt-5"   v-model="projects.country" :options="countries"  v-validate="'required'" ></v-select>
                <span class="text-danger text-sm">{{ errors[0] }}</span>


                <v-select  placeholder="Currencies" name="currency"   label="currency_name" class="w-full mb-4 mt-5"
                          v-model="projects.currency" :options="currencies"  v-validate="'required'" ></v-select>
                <span class="text-danger text-sm">{{ errors[0] }}</span>

                <v-select  placeholder="Timezones"   name="timezones"  label="time_zone" class="w-full mb-4 mt-5"
                          v-model="projects.timezone" :options="timezones"  v-validate="'required'" ></v-select>
                <span class="text-danger text-sm">{{ errors[0] }}</span>
              </div>
            </div>
          </form>
        </div>
      </vs-prompt>
    </div>
    </validation-provider>

    <b-navbar-nav class="nav align-items-center ml-auto">
      <locale />
      <dark-Toggler class="d-none d-lg-block" />
      <search-bar />
      <cart-dropdown />
      <notification-dropdown />
      <user-dropdown />
    </b-navbar-nav>
  </div>
</template>

<script>

import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  BLink, BNavbarNav,
} from 'bootstrap-vue'
import Bookmarks from './components/Bookmarks.vue'
import Locale from './components/Locale.vue'
import SearchBar from './components/SearchBar.vue'
import DarkToggler from './components/DarkToggler.vue'
import CartDropdown from './components/CartDropdown.vue'
import NotificationDropdown from './components/NotificationDropdown.vue'
import UserDropdown from './components/UserDropdown.vue'
import { BDropdown } from 'bootstrap-vue';
import {http} from '@/services/requests'

export default {
   data () {
    return {
      activePrompt: false,
      projects: {
        name: '',
        website: '',
        country: '',
        currency: '',
        timezone: '',
      },
      currencies:[],
      timezones:[],
      countries: [],
      projectsData: [],
      selectedProject: 'Select  Project',
      search: ''
    }
  },
  components: {
    BLink,
    BDropdown,
    // Navbar Components
    BNavbarNav,
    Bookmarks,
    Locale,
    SearchBar,
    DarkToggler,
    CartDropdown,
    NotificationDropdown,
    UserDropdown,
    ValidationProvider,
    ValidationObserver
  },
  props: {
    toggleVerticalMenuActive: {
      type: Function,
      default: () => {},
    },
  },
  computed: {
    searchProject() {
      return Array.isArray(this.projectsData) ? 
      this.projectsData.filter(project => {
        return project.name.toLowerCase().includes(this.search.toLowerCase())
      }) : []
    },

    navbarSearchAndPinList () {
      return {pages: this.$store.state.navbarSearchAndPinList['pages']}
    },
    starredPages () {
      return this.$store.state.starredPages
    },
    starredPagesLimited: {
      get () {
        return this.starredPages.slice(0, 10)
      },
      set (list) {
        this.$store.dispatch('arrangeStarredPagesLimited', list)
      }
    },
    starredPagesMore: {
      get () {
        return this.starredPages.slice(10)
      },
      set (list) {
        this.$store.dispatch('arrangeStarredPagesMore', list)
      }
    },
    textColor () {
      return {'text-white': this.$store.state.mainLayoutType === 'vertical' && this.navbarColor !== (this.$store.state.theme === 'dark' ? '#10163a' : '#fff') }
    },

    validateForm () {
      return this.projects.name !== '' && this.projects.website !== ''
        && this.projects.country !== '' && this.projects.currency !== ''  && this.projects.timezone !== ''
    }
  },
  methods: {
    selected (obj) {
      this.$store.commit('TOGGLE_CONTENT_OVERLAY', false)
      this.$router.push(obj.pages.url).catch(() => {})
    },
    hnd_search_query_update (query) {
      // Show overlay if any character is entered
      this.$store.commit('TOGGLE_CONTENT_OVERLAY', !!query)
    },

    clearFields () {
        this.projects.name = '';
        this.projects.website = '';
        this.projects.timezone = '';
        this.projects.country = '';
        this.projects.currency = '';
    },

    chooseCountry(event) {
      this.getTimezones(event)
      this.getCurrencies(event)
    },

    getCountries() {
      http.get('countries').then(response => {
        this.countries = response.data;

      }).catch(error => {

      });
    },

    getCurrencies(country) {
      let _this = this;
      http.get('currencies').then(response => {
        _this.currencies = response.data;
        response.data.forEach(function(entry) {
          if(entry.state == country.country_name){
            _this.projects.currency = entry
          }
        });
      }).catch(error => {

      });
    },

    getTimezones(country) {
      let _this = this;
      http.get('timezones').then(response => {
        _this.timezones = response.data;
        response.data.forEach(function(entry) {
          if(entry.country_code == country.code){
            _this.projects.timezone = entry

          }
        });
      }).catch(error => {

      });
    },

    getProjects() {
      let _this = this;
      http.get('projects').then(response => {
        _this.projectsData = response.data;
        response.data.forEach(function(entry) {
          if(entry.is_selected == 1){
            _this.selectedProject = entry.name

          }
        });
      }).catch(error => {

      });
    },

    selectedPro(project){
      this.selectedProject = project.name;
      http.get('projects/select/'+project.id_project).then(response => {
        localStorage.setItem('selectedProjectId', project.id_project);
      }).catch(error => {

      });
      this.$router.push({ path: '/dashboard/ecommerce/'+ project.token })
    },

    addProject () {
      let _this = this;
      // this.$validator.validateAll().then(result => {
        http.post('projects', this.projects).then(response => {
          _this.projects.name = '';
          _this.projects.website = '';
          _this.projects.timezone = '';
          _this.projects.country = '';
          _this.projects.currency = '';

          this.$vs.notify({
            text:this.$i18n.t('domain_has_been_successfully_added'),
            color:'success',
            iconPack: 'feather',
            icon:'icon-check'
          })
          this.getProjects();
        }).catch(error => {

        });
      // })
    },

    checkDomain() {
      let domain;
      if (!this.projects)
        return;
      if (this.projects.website.indexOf("://") > -1) {
        domain = this.projects.website.split('/')[2];
      } else {
        domain = this.projects.website.split('/')[0];
      }

      this.projects.website = domain.split(':')[0];
    }
  },

  created () {
    this.getCountries();
    this.getProjects();
  },
}
</script>
