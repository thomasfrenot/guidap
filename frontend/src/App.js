import mapboxgl, { Marker, Map, Popup } from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";

export default {
  data() {
    return {
      loading: true,
      recreationParks: [],
      searchString: '',
      selectedActivities: [],
      activities: [{name: "Wakeboard", id: 191}, {name: "Kayak", id: 192}],
      aborter: null,
      markers: []
    }
  },

  mounted() {
    mapboxgl.accessToken = "pk.eyJ1IjoidGhvbWFzZnJlbm90IiwiYSI6ImNsZmJucGgzajBnb3IzcW4xbnN2b2F2dGsifQ.0aTnTeGPxyuz_rS9gpCq1A";

    this.map = new Map({
      container: "map",
      style: "mapbox://styles/mapbox/light-v9",
      center: [2.1, 47.1],
      zoom: 4
    });

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

          this.recreationParks = await response.json();
          this.loading = false;         
          
          this.markers.map(m => m.remove());
          this.recreationParks.results.map(rp => {
            const popup = new Popup()
              .setHTML(`<h3>${rp.name}</h3><p>${rp.description}</p><p><a href="${rp.website}">Accéder au site web</a></p>`);
              popup.on('open', () => {
                
              })

            const marker = new Marker({
              color: "#fbbd0b"
            })
              .setLngLat([rp.latitude, rp.longitude])
              .setPopup(popup)
              .addTo(this.map);

            this.markers.push(marker);
          })
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
     * Trigger click tag
     */
    clickTag(activitySlug) {
      this.selectedActivities = [activitySlug];
      this.getRecreationParks();
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
