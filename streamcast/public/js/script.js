/**
 *
 * @param {*} time
 * @returns
 */
function toHHMMSS(time) {
  var sec_num = parseInt(time, 10); // don't forget the second param
  var hours = Math.floor(sec_num / 3600);
  var minutes = Math.floor((sec_num - hours * 3600) / 60);
  var seconds = sec_num - hours * 3600 - minutes * 60;

  if (hours < 10) {
    hours = "0" + hours;
  }
  if (minutes < 10) {
    minutes = "0" + minutes;
  }
  if (seconds < 10) {
    seconds = "0" + seconds;
  }
  return minutes + ":" + seconds;
}

document.addEventListener("DOMContentLoaded", function () {
  const streamCastPlayers = document.querySelectorAll(".sc_radio");

  streamCastPlayers.forEach((item) => {
    const audio = item.querySelector(".player");
    const player = new Plyr(audio, {
      controls: ["play", "current-time", "mute", "volume"],
    });
    player.on("ready", function () {
      const iconPressed = item.querySelector(".icon--pressed");
      const iconNotPressed = item.querySelector(".icon--not-pressed");
      const timeEl = item.querySelector(".plyr__time--current");
      let playing = true;
      let currentTime = player.currentTime;

      player.on("pause", function (e) {
        player.play();
        currentTime = e.timeStamp;
        if (playing) {
          player.muted = true;
          playing = false;
          iconNotPressed.style.display = "block";
          iconPressed.style.display = "none";
        } else {
          iconNotPressed.style.display = "none";
          iconPressed.style.display = "block";
          playing = true;
          player.muted = false;
        }
      });

      player.on("play", function () {
        // console.log(player.volume);
        if (player.volume == 0) {
          player.volume = 0.5;
        }
        if (playing) {
          player.muted = false;
        }
      });

      player.on("timeupdate", function (e) {
        timeEl.innerText = toHHMMSS(playing ? e.timeStamp / 1000 : currentTime / 1000);
      });
    });
  });
});
