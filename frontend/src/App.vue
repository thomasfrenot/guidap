<template>
  <div id="map" />
  <div>
    <InputText type="text" v-model="searchString" @input="handleSearch"></InputText>
    <MultiSelect v-model="selectedActivities" display="chip" :options="activities" optionValue="id" optionLabel="name" placeholder="Select activities" :maxSelectedLabels="3" />
  </div>
  <Card style="width: 25%">
    <template #title> Advanced Card </template>
    <template #content>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore sed consequuntur error repudiandae numquam deserunt quisquam repellat libero asperiores earum nam nobis, culpa ratione quam perferendis esse, cupiditate neque
            quas!
        </p>
    </template>
    <template #footer>
        <Tag :style="{background: 'red'}" value="Canoë"></Tag>
    </template>
  </Card>
</template>
<script>
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
      });
      map.on('load', () => {
      // TODO: Here we want to load a layer
      // TODO: Here we want to load/setup the popup
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

  methods: {
    handleSearch() {
      console.log(this.searchString)
    }
  }
}
</script>

<style>
#map {
  height: 350px;
}
</style>
