"use strict";

class Mot {
  mot; //mot sélectionné
  motId; //id du mot dans la BDD
  nbLetters; //nombre de lettres choisi pour le mot
  dico; //tableau des mots de la longueur choisie

  constructor(a = 6) {
    this.nbLetters = a;
    this.dico = [];
    this.mot = "";
  }

  //méthodes
  async init() {
    await this.loadDico(this.nbLetters);
    this.pickUp();
  }
  //on extrait un tableau des mots de la longueur demandée dans la BDD
  async loadDico(a) {
    const response = await fetch("/dictionary/jsonQuery/" + a, {
      method: "GET",
    });
    const dicoDB = await response.json();
    // this.dico = dicoDB.map((word) =>
    //   word
    //     .normalize("NFD")
    //     .replace(/[\u0300-\u036f]/g, "")
    //     .toUpperCase()
    // );
    for (let id in dicoDB) {
      let word = dicoDB[id]
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .toUpperCase();
      this.dico[id] = word;
    }
    // console.log(this.dico);
  }

  //random select un mot dans la BD
  pickUp() {
    let keys = Object.keys(this.dico);
    let randomKey = keys[Math.floor(Math.random() * keys.length)];
    this.motId = randomKey; 
    this.mot = this.dico[randomKey]; 
      console.log("randomPick = id " + this.motId + " : " + this.mot);
  }

  isInDB(input) {
    console.log(
      `le mot saisi est dans le dico? : ${this.dico.includes(input)}`
    );
    return this.dico.includes(input);
  }
}
export { Mot };
