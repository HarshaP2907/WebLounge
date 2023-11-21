var elem = document.getElementById("chk");

function func() {
  document.getElementById("machine").style.fill = elem.checked
    ? "white"
    : "black";
}

const cover = document.querySelector(".cover");
const machine1 = document.querySelector("#machine1");

let counter = 1;

$("#changePage").on("click", function () {
  if (counter % 2 === 1) {
    // document.getElementById("machine").style.fill="white";
    anime({
      targets: cover,
      borderRadius: [{ value: 0 }, { value: "100%" }, { value: 0 }],
      translateX: [{ value: -500 }],
      scaleX: [{ value: 3 }, { value: 1 }],
      borderRadius: ["0%", "600%", "0%"],
      // height: [
      //   {value: '480px'},
      //   {value: '300px'},
      //   {value: '480px'}
      // ],
      // alignItems: 'center',
      // justifyContent: 'center',
      // innerHeight: [
      easing: "linear",
      // borderRadius:'50%'

      // anime({
      //   targets: ".nameLaundreasy",
      //   strokeDashoffset: [anime.setDashoffset, 0],
      //   easing: 'easeInOutSine',
      //   duration: 1500,
      //   delay: function(el, i) { return i * 250 },
      //   direction: 'alternate',
      //   loop: true
      // });
    });
    anime({
      targets: ".loginPage",
      opacity: 0,
    });
    anime({
      targets: ".registerPage",
      opacity: 1,
      easing: "linear",
    });
    anime({
      targets: ".rightWelcome",
      opacity: 0,
      translateX: [{ value: 300 }],
      duration: 400,
      easing: "linear",
    });
    anime({
      targets: ".leftWelcome",
      translateX: [{ value: 0 }],
      marginLeft: "0px",
      opacity: 1,
      easing: "linear",
    });
    anime({
      targets: ".nameWebLounge",
      color: "#fff",
    });
    anime({
      targets: machine1,
      fill: "#fff",
    });

    counter += 1;
  } else {
    anime({
      targets: cover,
      translateX: [{ value: 0 }],
      scaleX: [{ value: 3 }, { value: 1 }],
      easing: "linear",
      borderRadius: ["0%", "600%", "0%"],
      // borderRadius: '50%',
    });
    anime({
      targets: ".loginPage",
      opacity: 1,
      easing: "linear",
    });
    anime({
      targets: ".registerPage",
      opacity: 0,
    });
    anime({
      targets: ".leftWelcome",
      translateX: [{ value: -300 }],
      marginLeft: "-300px",
      opacity: 0,
      duration: 400,
      easing: "linear",
    });
    anime({
      targets: ".rightWelcome",
      opacity: 1,
      translateX: [{ value: 0 }],
      easing: "linear",
    });
    anime({
      targets: ".nameWebLounge",
      color: "#1F4287",
    });
    anime({
      targets: machine1,
      fill: "#000",
    });
    counter += 1;
  }
});
