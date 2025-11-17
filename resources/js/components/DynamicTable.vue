<template>
  <table id="tblAppendGrid"></table>
</template>





<script>



export default { 
  name: "DynamicTable",

  props: {
    columns: { type: Array, required: true },
    initRows: { type: Number, default: 1 },
    data: { type: Array, default: () => [] },
    gridId: { type: String, default: "appendGrid_" + Math.random().toString(36).substr(2, 9) }
  },

  mounted() {

    const vm = this;

    // Initialize AppendGrid
    var myAppendGrid = new AppendGrid({
    element: "tblAppendGrid",
        columns: [
            { name: "foo", display: "Foo" },
            { name: "bar", display: "Bar" }
        ],
        initData: [
            { foo: "Foo Data 1", bar: "Bar Data 1" },
            { foo: "Foo Data 2", bar: "Bar Data 2" },
            { foo: "Foo Data 3", bar: "Bar Data 3" }
        ]
    });
  },

  beforeUnmount() {
    // Destroy AppendGrid to avoid memory leaks
    $("#gridId").appendGrid("remove");
  },

  methods: {
    getValues() {
      return $("#gridId").appendGrid("getAllValue");
    },
    emitData() {
      this.$emit("update:data", this.getValues());
    }
  }
};
</script>

<style scoped>
/* Optional custom styling */
</style>
