<template>
  <header>
    <img src="https://guidap.com/wp-content/uploads/2018/07/cropped-200-px-blanc-signature-mail-1-1.png">
    <h1>Test technique</h1>
  </header>
  <div id="map" />
  <div class="filters">
    <InputText
      v-model="searchString"
      type="text"
      placeholder="Recherche par nom ou description"
      @input="handleSearch"
    />
    <MultiSelect
      v-model="selectedActivities"
      class="select"
      :options="activities"
      option-value="slug"
      option-label="name"
      placeholder="Filtre par activité"
      :max-selected-labels="3"
      @change="changeActivities"
    />
  </div>
  <div class="wrapper">
    <div
      v-if="recreationParks?.length"
      class="cards"
    >
      <PrimeCard
        v-for="recreationPark in recreationParks"
        :key="recreationPark.slug"
        @click="openMarkerPopup(recreationPark)"
      >
        <template #title>
          {{ recreationPark.name }}
        </template>
        <template #content>
          <p>
            {{ recreationPark.description || '...' }}
          </p>
          <p><a href="{{ recreationPark.website }}">Accéder au site web</a></p>
        </template>
        <template #footer>
          <PrimeTag
            v-for="activity in recreationPark.activities"
            :key="activity.id"
            @click="clickTag(activity.slug)"
          >
            {{ activity.slug }}
          </PrimeTag>
        </template>
      </PrimeCard>
    </div>
    <div
      v-if="0 === recreationParks.length && false === loading"
      class="cards-empty"
    >
      <div>
        Aucun résultat
      </div>
    </div>
    <div
      class="cards-empty"
    >
      <div v-if="loading">
        <ProgressSpinner
          style="width: 50px; height: 50px;"
          stroke-width="5"
          fill="#fbbd0b"
        />
      </div>
    </div>
  </div>
</template>
<script src="./App.js"></script>
<style src="./App.scss"></style>
