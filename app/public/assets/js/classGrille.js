"use strict";
class Grille {
  idDom; //id de la zone où afficher la grille
  nbSlots; //nombre de cases lettres à afficher
  //   firstSlot; //la 1ere case est automatiquement affichée sur chaque lignes
  frameClass; //sélecteur de classe CSS pour la zone de jeu
  lineClass; //sélecteur de classe CSS pour une ligne
  letterClass; //sélecteur de classe CSS pour une case lettre
  letterOkClass;
  letterMisplacedCLass;
  letterNOK;
  tabWord; //mot à trouver converti en tableau

  // méthodes
  generate() {
    //générer la grille display
    let gameGrid = document.getElementById(this.idDom);
    gameGrid.innerHTML = "";
    gameGrid.className = this.frameClass;
    //1ère ligne :
    let firstLine = document.createElement("div");
    firstLine.className = this.lineClass;
    for (let i = 0; i < this.tabWord.length; i++) {
      let letterBox = document.createElement("div");
      letterBox.className = this.letterClass;
      if (i == 0) {
        letterBox.innerText = this.tabWord[0];
      } else {
        letterBox.innerText = ".";
      }
      firstLine.appendChild(letterBox);
    }
    gameGrid.appendChild(firstLine);
  }

  update(input, tabLettersOK = [], tabMisplaced = [], isInDict) {
    //ajoute une ligne/mot à la grille
    let tabInput = input.split("");
    let gameGrid = document.getElementById(this.idDom);
    let NewLine = document.createElement("div");
    NewLine.className = this.lineClass;
    for (let i = 0; i < this.tabWord.length; i++) {
      let letterBox = document.createElement("div");
      letterBox.className = this.letterClass;
      letterBox.innerText = tabInput[i];
      if (isInDict) {
        if (tabLettersOK[i]) {
          letterBox.className += " " + this.letterOkClass;
        }
        if (tabMisplaced[i]) {
          let span = document.createElement("span");
          span.className = this.letterMisplacedCLass;
          letterBox.innerText = "";
          span.innerText = tabInput[i];
          letterBox.appendChild(span);
        }
      }
      if (!isInDict) {
        letterBox.className += " " + this.letterNOK;
      }
      NewLine.appendChild(letterBox);
    }
    gameGrid.appendChild(NewLine);
  }
}
export { Grille };
