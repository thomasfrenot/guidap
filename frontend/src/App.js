import mapboxgl, { Marker, Map, Popup } from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import { ref, toRaw } from 'vue';

export default {
  setup() {
    const recreationParks = ref([]);

    return {
      recreationParks
    }
  },

  data() {
    return {
      map: null,
      loading: true,
      recreationParks: [],
      searchString: '',
      selectedActivities: [],
      activities: [{name: "Wakeboard", id: 191}, {name: "Kayak", id: 192}],
      aborter: null,
      markers: [],
      popups: [],
      page: 1,
      totalPages: 1,
      isLoading: false
    }
  },

  mounted() {
    mapboxgl.accessToken = import.meta.env.VITE_API_GEOCODING;

    this.map = new Map({
      container: "map",
      style: "mapbox://styles/mapbox/light-v9",
      center: [2.1, 47.1],
      zoom: 4
    });

    this.getRecreationParks();
    this.getActivities();

    window.addEventListener('scroll', this.handleScroll);
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
            page: this.page
        })

        this.loading = true;

        try {
          const response = await fetch(import.meta.env.VITE_API_BASEURL + '/recreation-parks?' + params, { signal });

          if (!response.ok) {
              const message = `An error has occured: ${response.status}`;
              throw new Error(message);
          }

          const data = await response.json();
          this.loading = false;
          this.totalPages = data.totalPages;

          this.markers.map(m => m.remove());
          this.recreationParks = [...this.recreationParks, ...data.results];
          this.recreationParks.map(rp => {
            const popup = new Popup()
            .setHTML(`<h3>${rp.name}</h3><p>${rp.description}</p><p><a href="${rp.website}">Accéder au site web</a></p>`);
            rp.popup = popup;

            const marker = new Marker({
              color: "#fbbd0b"
            })
              .setLngLat([rp.latitude, rp.longitude])
              .setPopup(popup)
              .addTo(this.map);

            this.popups.push(popup);
            this.markers.push(marker);
            rp.marker = marker;
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

    handleScroll() {
      const currentScrollPosition = window.pageYOffset + window.innerHeight + 10;
      const pageHeight = document.documentElement.scrollHeight;
      if (currentScrollPosition >= pageHeight && false === this.loading && this.totalPages > this.page) {
        this.page++;
        this.getRecreationParks();
      }
    },

    /**
     * Trigger click on recreationPark card
     */
    openMarkerPopup(recreationPark) {
      this.popups.map(p => p.remove());
      toRaw(recreationPark).marker.togglePopup();
      this.map.setCenter(toRaw(recreationPark).marker.getLngLat());
      if (8 > this.map.getZoom()) {
        this.map.setZoom(8);
      }
    },

    /**
     * Trigger click tag
     */
    clickTag(activitySlug) {
      this.page = 1;
      this.selectedActivities = [activitySlug];
      this.recreationParks = [];
      this.getRecreationParks();
    },

    /**
     * Trigger for input search
     */
    handleSearch() {
        if (2 < this.searchString.length || 0 === this.searchString.length) {
          this.page = 1;
          this.recreationParks = [];
          this.getRecreationParks();
        }
    },

    /**
     * Trigger for activities select change
     */
    changeActivities() {
      this.page = 1;
      this.recreationParks = [];
      this.getRecreationParks();
    }
  }
}
