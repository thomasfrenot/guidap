import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";
import { onMounted } from "vue";

export default {
  setup() {
    onMounted(() => {
      mapboxgl.accessToken = "pk.eyJ1IjoidGhvbWFzZnJlbm90IiwiYSI6ImNsZmJucGgzajBnb3IzcW4xbnN2b2F2dGsifQ.0aTnTeGPxyuz_rS9gpCq1A";

      new mapboxgl.Map({
        container: "map",
        style: "mapbox://styles/mapbox/light-v9",
        center: [2.1, 47.1],
        zoom: 4
      });

      return {};
    })
  },

  data() {
    return {
      loading: true,
      recreationParks: [],
      searchString: '',
      selectedActivities: [],
      activities: [{name: "Wakeboard", id: 191}, {name: "Kayak", id: 192}],
      aborter: null
    }
  },

  mounted() {
    this.getRecreationParks();
    this.getActivities();
  },

  methods: {
    /**
     * Get recreationParks from API
     */
    async getRecreationParks() {

      // abort fetch request if another call launch before API response
        if(this.aborter) this.aborter.abort();
        this.aborter = new AbortController();
        let signal = this.aborter.signal;

        const params = new URLSearchParams({
            limit: 3,
            activities: this.selectedActivities.join(','),
            search: 2 < this.searchString.length ? this.searchString : '',
            page: 1
        })

        this.loading = true;
        this.recreationParks = [];

        try {
          const response = await fetch(import.meta.env.VITE_API_BASEURL + '/recreation-parks?' + params, { signal });

          if (!response.ok) {
              const message = `An error has occured: ${response.status}`;
              throw new Error(message);
          }

          setTimeout(async () => {
            this.recreationParks = await response.json();
            this.loading = false;
          }, 500)
          
        } catch (error) {
          console.error(error);
        }
        
    },

    /**
     * Get activities list from API
     */
    async getActivities() {
        const response = await fetch(import.meta.env.VITE_API_BASEURL + '/activities');

        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            throw new Error(message);
        }

        this.activities = await response.json();
    },

    /**
     * Trigger for input search 
     */
    handleSearch() {
        if (2 < this.searchString.length || 0 === this.searchString.length) {
            this.getRecreationParks();
        }
    },
    
    /**
     * Trigger for activities select change
     */
    changeActivities() {
      this.getRecreationParks();
    }
  }
}
