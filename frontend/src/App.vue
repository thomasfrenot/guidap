<template>
  <header>
    <img src="https://media.glassdoor.com/sql/2405674/guidap-squarelogo-1635507503270.png"/>
    <h1>Test technique</h1>
  </header>
  <div id="map" />
  <div class="filters">
    <InputText type="text" placeholder="Recherche par nom ou description" v-model="searchString" @input="handleSearch"></InputText>
    <MultiSelect class="select" @change="changeActivities" v-model="selectedActivities" :options="activities" optionValue="slug" optionLabel="name" placeholder="Filtre par activité" :maxSelectedLabels="3" />
  </div>
  <div class="cards" v-if="this.recreationParks?.results?.length">
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
