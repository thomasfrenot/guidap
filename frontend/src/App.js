import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";
import { onMounted } from "vue";

export default {
  setup() {
    onMounted(() => {
      mapboxgl.accessToken = "pk.eyJ1IjoidGhvbWFzZnJlbm90IiwiYSI6ImNsZmJucGgzajBnb3IzcW4xbnN2b2F2dGsifQ.0aTnTeGPxyuz_rS9gpCq1A";

      const map = new mapboxgl.Map({
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
      recreationParks: [],
      searchString: '',
      selectedActivities: [],
      activities: [{name: "Wakeboard", id: 191}, {name: "Kayak", id: 192}]
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
        const params = new URLSearchParams({
            limit: 2,
            activities: this.selectedActivities.join(','),
            search: 2 < this.searchString.length ? this.searchString : ''
        })

        const response = await fetch(import.meta.env.VITE_API_BASEURL + '/recreation-parks?' + params);

        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            throw new Error(message);
        }

        this.recreationParks = await response.json();
    },

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
        if (2 < this.searchString.length) {
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