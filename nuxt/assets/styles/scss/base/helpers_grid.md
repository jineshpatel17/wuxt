---
name: Grid
category: Helper Classes
---

## BEM Color classes

Each of the color variables have been mapped to BEM style classes that can be added
to any component or element.

Styling for colors can be added to the sass for the components themselves,
however, in some instances it is better to add a modifier class to the component.

The available classes are broken down into 3 categories of helper class:

```scss
// Backgrounds
.block--full-width
```

```grid-helper-classes.html
<div class="row">
  <div class="example grid-example bdr--light-beta block--full-width">
    <div class="grid-content">
      <p><strong class="annotation">.block--full-width</strong></p>
      <p>Full width blocks include the padding required for each breakpoint</p>
      <p>They can be wrapped with the `row` class to give them a fixed max width.</p>
    </div>
  </div>
</div>
```
