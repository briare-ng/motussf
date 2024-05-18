"use strict"; // Oblige la déclaration des variables
import { Mot } from "./classMot.js";
import { Grille } from "./classGrille.js";

let word = {};
let grid = {};
let tabLettresTrouvees = [];
let tabLettresMalplacees = [];
let isSucceed = false;
let isInDict = false;
let gameOver = false;
let attemptMax; //nb de tentatives autorisées dans le jeu
let attemptCount; //nb de tentatives du joueur
let difficulty = document.getElementById("difficulte");
let difficultyLevel = 6;
let btnValidation = document.getElementById("validation");
let buttonV = document.getElementById("validation");
let form = document.querySelector("form");
let formInput = document.getElementById("suggestion");
let gameMsg = document.getElementById("gameMsg");
let replayBtn = document.getElementById("replay");

//nettoie et valide l'entrée de l'utilisateur
function sanitizeInput(input) {
  input = input.trim(); //on supprime les espaces avant et après
  input = input
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toUpperCase();

  //+autres filtres, regEx etc...
  let regEx = /^[a-zA-Z]+$/; //pour vérifier qu'il n'y a qu'un seul mot sans caractères spéciaux
  let msg = document.getElementById("msg");
  if (!input.match(regEx)) {
    //afficher un message d'erreur ou lever un erreur try/catch
    console.log("erreur:entrée non valide");
    msg.innerHTML =
      '<i class="bi bi-x-circle mt-1"></i> Les chiffres et les caractères spéciaux ne sont pas autorisés';
    return null;
  }
  // et/ou alors utiliser l'attribut Html pattern avec un regEx et une longueur {nb}
  // console.log(input.length + " longueur");
  else if (input.length != difficultyLevel) {
    msg.innerHTML =
      '<i class="bi bi-x-circle mt-1 fw-semibold"></i> Votre mot n\'est pas de la bonne longueur';
    return null;
  } else {
    msg.innerHTML = "";
  }
  return input;
}

//compare l'entrée de l'utilisateur avec le mot à trouver
function compare(motATrouver, input) {
  for (let i = 0; i < motATrouver.length; i++) {
    tabLettresTrouvees[i] = false;
    tabLettresMalplacees[i] = false;
  }
  //je compte le nb de fois qu'une lettre apparait dans le mot à trouver
  let countLetters = {};
  for (let i = 0; i < motATrouver.length; i++) {
    let lettre = motATrouver[i];
    if (countLetters[lettre]) {
      countLetters[lettre]++;
    } else {
      countLetters[lettre] = 1;
    }
  }
  console.log(countLetters);
  for (let i = 0; i < input.length; i++) {
    let lettre = input[i];
    //vérifier une à une que les lettres sont dans le mot à trouver
    if (motATrouver[i] == lettre) {
      //la lettre est bien placée on maj le tabLettresTrouvees
      tabLettresTrouvees[i] = true;
      countLetters[lettre]--; //décrémente le compteur pour cette lettre
    }
  }
  for (let i = 0; i < input.length; i++) {
    let lettre = input[i];
    if (countLetters[lettre] >= 1 && !tabLettresTrouvees[i]) {
      //la lettre est mal placée on maj le tab tabLettresMalplacees
      tabLettresMalplacees[i] = true;
      countLetters[lettre]--;
    }
  }
  console.log(tabLettresTrouvees);
  console.log(tabLettresMalplacees);
  console.log(countLetters);
}

//vérifie si la partie est finie
function isGameOver(nbtentatives, tabLettresOK) {
  //est ce que toutes les lettres sont trouvées dans le nombre d'essais autorisé?
  //attemptCount<6? continue game
  if (nbtentatives < attemptMax) {
    isSucceed = true;
    for (let i = 0; i < tabLettresOK.length; i++) {
      if (!tabLettresOK[i]) {
        isSucceed = false;
        return false;
      }
    }
    return true;
  }

  //attemptCount>6? game over you loose
  if (nbtentatives >= attemptMax) {
    isSucceed = false;
    return true;
  }
  //isSucceed == true  game over you win
  console.log(`le jeu est gagné? : ${isSucceed}`);
}

// affichage de la grille + avec choix d'un mot selon la difficulté
async function initGame(difficultyLevel) {
  word = new Mot(difficultyLevel);
  await word.init();
  grid = new Grille();
  grid.nbSlots = word.nbLetters;
  grid.idDom = "game";
  grid.frameClass = "gameFrame";
  grid.tabWord = word.mot.split("");
  grid.lineClass = "line";
  grid.letterClass = "letter";
  grid.letterOkClass = "letterOK";
  grid.letterMisplacedCLass = "letterMisplaced";
  grid.letterNOK = "letterNOK";
  grid.generate();
  console.log(`le mot à trouver est : ${word.mot}`);
  replayBtn.classList.add("hidden");
  form.classList.remove("hidden");
  attemptCount = 0;
  gameMsg.innerText = "";
  buttonV.classList.add("hidden");
  formInput.focus();
  // console.log("element en focus : " + document.activeElement);
}

/**
 * envoi vers le backEnd pour traitement avant insertion en BD
 * @param {*} points nombre de points à envoyer
 * @param {*} word_id ID du mot à envoyer
 */
function insertScore(points, word_id) {
  fetch(`/scores/addScore/${points}/${word_id}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.text())
    .then((data) => console.log(data))
    .catch((error) => {
      console.error("Erreur à l'insertion en BDD :", error);
    });
}

/////////////////////      programe principal du jeu    ////////////////////////////
window.addEventListener("load", async (event) => {
  ////initialisation du jeu
  console.log("DOM loaded");
  await initGame();
  attemptMax = 6; //réglage du nb de suggestions maximum
  console.log(`1st init : ${word.mot}`);

  //modif du mot et de la grille si la difficultée est modifiée par le joueur
  difficulty.addEventListener("change", (e) => {
    difficultyLevel = difficulty.value;
    console.log(`la difficulté est réglée à ${difficultyLevel}`);
    //recharger le jeu avec une nouvelle difficulté
    initGame(difficultyLevel);
    // régler les attributs de l'input
    formInput.setAttribute("minlength", difficultyLevel);
    formInput.setAttribute("maxlength", difficultyLevel);
    gameMsg.innerText = "";
  });

  //afficher/effacer le bouton de soumission du form si les conditions de validations sont respectées
  form.addEventListener("keyup", (e) => {
    if (form.checkValidity()) {
      // Le formulaire est valide on affiche le btn de sousmission
      buttonV.classList.remove("hidden");
    } else {
      buttonV.classList.add("hidden");
    }
  });

  //évènement sur une suggestion de mot
  btnValidation.addEventListener("click", (e) => {
    //déclenchement de la validation html
    if (!form.checkValidity()) {
      // Le formulaire n'est pas valide, donc on ne fait rien et on laisse le navigateur afficher les messages d'erreur de validation.
      return;
    }
    e.preventDefault(); //si le btn est de type submit on ne recharge pas la page
    let input = formInput.value;
    input = sanitizeInput(input);
    console.log(input + " log2");

    //le script ne se poursuis pas si le mot n'est pas validé par sanitizeInput()
    if (input == null) {
      console.log(
        "Erreur de validation de l'entrée : annulation de la soumission du mot!"
      );
      document.getElementById("suggestion").value = "";
      return; // Arrête la soumission du formulaire
    }

    isInDict = word.isInDB(input);
    if (!isInDict) {
      msg.innerHTML =
        '<i class="bi bi-x-circle mt-1"></i> Ce mot n\'existe pas dans notre dictionnaire';
    } else {
      msg.innerHTML = "";
    }
    compare(word.mot, input);
    grid.update(input, tabLettresTrouvees, tabLettresMalplacees, isInDict);
    attemptCount++;
    console.log(`nombres de tentatives du joueur : ${attemptCount}`);
    //efface la zone de saisie
    document.getElementById("suggestion").value = "";

    gameOver = isGameOver(attemptCount, tabLettresTrouvees);
    console.log(
      `le jeu est fini? ${gameOver} , le jeu est gagné? : ${isSucceed}`
    );
    //cloture de la partie, empecher d'autres tentatives de jeu
    if (!gameOver && !isSucceed) {
      gameMsg.innerText = `La partie continue... Il vous reste ${
        attemptMax - attemptCount
      } tentatives`;
    }
    if (gameOver && !isSucceed) {
      form.classList.add("hidden");
      gameMsg.innerText = `You Loose! Le mot était : ${word.mot}`;
      replayBtn.classList.remove("hidden");
      replayBtn.focus();
    }
    if (isSucceed) {
      form.classList.add("hidden");
      let score =
        (attemptMax - attemptCount + 1) * attemptMax * difficultyLevel;
      gameMsg.innerText = `You win! Bravo, tu as marqué ${score} points !`;
      replayBtn.classList.remove("hidden");
      replayBtn.focus();
      //enregistre le score du joueur en BDD via fetch method:post
      insertScore(score, word.motId);
      console.log(
        "le score est : " +
          score +
          "points, pour le mot : " +
          word.mot +
          " (word_id : " +
          word.motId +
          ")"
      );
    }
  });

  replayBtn.addEventListener("click", () => {
    initGame(difficultyLevel);
  });
});
