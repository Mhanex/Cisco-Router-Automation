function initExamTimer(secondsLeft, onExpire) {
  const timerEl = document.getElementById('timer');
  let remaining = secondsLeft;
  const tick = () => {
    const mins = Math.floor(remaining / 60);
    const secs = remaining % 60;
    timerEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    if (remaining <= 0) {
      clearInterval(id);
      onExpire();
    }
    remaining--;
  };
  tick();
  const id = setInterval(tick, 1000);
}
