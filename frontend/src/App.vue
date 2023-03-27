<template>
  <header>
    <h1>Guidap test</h1>
  </header>
  <div id="map" />
  <div class="flex">
    <InputText type="text" v-model="searchString" @input="handleSearch"></InputText>
    <MultiSelect @change="changeActivities" v-model="selectedActivities" display="chip" :options="activities" optionValue="slug" optionLabel="name" placeholder="Select activities" :maxSelectedLabels="3" />
  </div>
  <div class="flex" v-if="this.recreationParks?.results?.length">
    <Card v-for="recreationPark in this.recreationParks?.results">
      <template #title> {{ recreationPark.name }} </template>
      <template #content>
          <p>{{ recreationPark.description || '...' }}</p>
      </template>
      <template #footer>
          <Tag v-for="activity in recreationPark.activities">{{ activity.name }}</Tag>
      </template>
    </Card>
  </div>
  <div class="flex center" v-else>Aucun centre de loisir trouvé pour votre recherche</div>
</template>
<script src="./App.js"></script>
<style src="./App.scss"></style>
