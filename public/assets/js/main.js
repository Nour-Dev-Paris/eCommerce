let d1 = document.getElementById("link_panier");
let p1 = document.getElementById("block_panier");

// d1.addEventListener("mouseover", () => {p1.style.display = "block"; p1.style.transition = "10s";});
// d1.addEventListener("mouseout", () => {p1.style.display = "none";});
d1.addEventListener('mouseover', function() {
  p1.classList.add('visible');
});
d1.addEventListener('mouseout', function() {
  p1.classList.remove('visible');
});