var block1 = document.getElementById('block1'),
	block2 = document.getElementById('block2'),
	block3 = document.getElementById('block3');
	block4 = document.getElementById('block4');
	block5 = document.getElementById('block5');


new Sortable(block1, {
	group: 'shared', // set both lists to same group
	ghostClass: 'blue-background-class',
	onStart: function (evt) {
		console.log(evt.oldIndex);
	},
	animation: 150
});

new Sortable(block2, {
	group: 'shared',
	ghostClass: 'blue-background-class',
	animation: 150
});

new Sortable(block3, {
	group: 'shared',
	ghostClass: 'blue-background-class',
	animation: 150
});

new Sortable(block4, {
	group: 'shared',
	ghostClass: 'blue-background-class',
	animation: 150
});

new Sortable(block5, {
	group: 'shared',
	ghostClass: 'blue-background-class',
	animation: 150
});