<template>
  <div class="q-pa-sm bg-brown-6 max-heigth">
    <div id="svgDiv"></div>
    <div class="flex justify-center">
      <q-select
        v-model="selected"
        :options="options"
        label="Pantalla"
        style="background-color: white; width: 200px"
        class="q-mr-md rounded-borders"
        id="bar"
      />
      <q-fab
        v-model="this.options"
        label="Opciones"
        label-position="left"
        color="purple"
        icon="keyboard_arrow_right"
        direction="right"
      >
        <q-fab-action
          v-if="this.admin == true"
          color="primary"
          to="/erp/restaurant-panel"
          icon="admin_panel_settings"
          label="Admin Panel"
        />
        <q-fab-action
          color="negative"
          @click="logOut()"
          icon="logout"
          label="Cerrar Sesión"
        />
      </q-fab>
    </div>
    <q-dialog v-model="dialog" :backdrop-filter="this.backdrop">
      <q-card>
        <q-card-section class="row items-center q-pb-none text-h6">
          Table Actions
        </q-card-section>

        <q-card-section>
          <q-btn
            label="Make reserve"
            @click="getHoursOnDialog()"
            color="primary"
            class="q-mx-sm"
          />
          <q-btn
            label="Confirm assis"
            @click="confirmTableAssistance()"
            color="green"
            v-if="confirmAsis == true"
            class="q-mx-sm"
          />
          <q-btn
            label="Cancel Reservation"
            @click="cancelActualReservation()"
            color="red"
            v-if="cancelReservation == true"
            class="q-mx-sm"
          />
          <q-btn
            label="Ocupate table"
            @click="ocupateTable()"
            color="purple"
            v-if="ocupatedTable == true"
            class="q-mx-sm"
          />
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Close" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="dialogRed" :backdrop-filter="this.backdrop">
      <q-card>
        <q-card-section class="row items-center q-pb-none text-h6">
          Table Actions
        </q-card-section>

        <q-card-section>
          <q-btn label="Cancel Reserve" color="negative" />
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Close" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="dlSelecDiners" :backdrop-filter="this.backdrop">
      <q-card>
        <q-card-section class="row items-center q-pb-none text-h6">
          Diners
        </q-card-section>

        <q-card-section class="flex justify-center">
          <q-btn
            @click="setDiners(diners_num)"
            class="q-ma-sm boton diners-btn"
            v-for="diners_num in diners"
            :label="diners_num"
            :key="diners_num"
            v-close-popup
          ></q-btn>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Close" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="dlSelecHour" :backdrop-filter="this.backdrop">
      <q-card>
        <q-card-section class="row items-center q-pb-none text-h6">
          Hours
        </q-card-section>

        <q-card-section class="flex justify-center">
          <div class="row q-ma-md" v-for="horario in this.hours" :key="horario">
            <div class="col">
              <div class="row justify-center">
                <h6 class="rounded-borders q-ma-sm q-pa-sm horario-title">
                  {{ horario[0] }}
                </h6>
              </div>
              <div class="row justify-center card-body" v-if="this.reservation.date == this.today">
                <div v-for="hora in horario[1]" :key="hora">
                  <q-btn
                    v-if="this.hourCompareActual(hora)"
                    @click="updateHour(hora)"
                    :active="reservation.hour === hora"
                    :key="hora"
                    class="hora-btn"
                    v-close-popup
                    >{{ hora }}</q-btn
                  >
                  <q-btn
                    v-else
                    class="hora-btn"
                    disable
                    >{{ hora }}</q-btn
                  >
                </div>
              </div>
              <div class="row justify-center card-body" v-if="this.reservation.date != this.today">
                <div v-for="hora in horario[1]" :key="hora">
                  <q-btn
                    @click="updateHour(hora)"
                    :active="reservation.hour === hora"
                    class="hora-btn"
                    v-close-popup
                    >{{ hora }}</q-btn
                  >
                </div>
              </div>
            </div>
          </div>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Close" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="makeReserve" :backdrop-filter="this.backdrop">
      <q-card>
        <q-card-section class="row items-center q-pb-none text-h6">
          Make Reservation
        </q-card-section>

        <q-card-section>
          <q-form @submit="createReservation()">
            <div class="q-pa-md" style="max-width: 400px">
              <q-input
                filled
                v-model="reservation.date"
                mask="date"
                :rules="['date']"
              >
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      cover
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date v-model="reservation.date">
                        <div class="row items-center justify-end">
                          <q-btn
                            v-close-popup
                            label="Close"
                            color="primary"
                            flat
                          />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </div>
            <div class="flex q-pa-md justify-between">
              <q-btn
                label="Select hour"
                @click="this.dlSelecHour = true"
                color="primary"
              />
              <q-input
                filled
                v-model="reservation.hour"
                readonly
                style="width: 30%"
              ></q-input>
            </div>
            <div class="q-pa-md">
              <q-input
                filled
                v-model="reservation.userEmail"
                style="width: 100%"
                label="User Email"
                :rules="[(val) => !!val || 'Email required']"
              ></q-input>
            </div>
            <div class="flex q-pa-md justify-between">
              <q-btn
                label="Select diners"
                style="width: 40%"
                @click="this.dlSelecDiners = true"
                color="primary"
              />
              <q-input
                filled
                v-model="reservation.diners"
                style="width: 40%"
                label="Diners"
                readonly
                :rules="[(val) => !!val || 'Number of diners required']"
              ></q-input>
            </div>
            <q-card-actions align="right">
              <q-btn flat label="Close" color="primary" v-close-popup />
              <q-btn :loading="this.loadingBtnCR" label="Create Reservation" color="green" type="submit" />
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>
<script>
import { LocalStorage, Notify, Screen } from "quasar";
import { isEqual } from "lodash";
import moment from "moment";
import SvgComponent from "src/components/SvgComponent.vue";
import { apiUrl } from "src/boot/axios";
import { set } from "lodash";
import { method } from "lodash";
import { head } from "lodash";

export default {
  name: "RestSalesPage",
  components: {
    SvgComponent,
  },
  data() {
    return {
      restaurant: {},
      employee: {},
      svgs: {},
      selected: "",
      options: [],
      svg: {},
      token: "",
      restaurant_id: "",
      newSvgs: {},
      admin: false,
      options: false,
      dialog: false,
      dialogRed: false,
      makeReserve: false,
      dlSelecHour: false,
      dlSelecDiners: false,
      reservation: {
        date: "",
        hour: "",
        userEmail: "",
        table: "",
        diners: "",
        code: "",
        dsid: "",
        resid: "",
        usrid: 0,
      },
      hours: [],
      diners: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
      backdrop: "brightness(60%)",
      tableHoursOcupated: [],
      confirmAsis: false,
      cancelReservation: false,
      ocupatedTable: false,
      tableNextReserveHour: "",
      tableNextReserveKey: "",
      verify : false,
      loadingBtnCR : false,
      today : ""
    };
  },
  watch: {
    selected(newVal, oldVal) {
      // console.log('Valor nuevo: ' + newVal);
      this.svgs.forEach((element) => {
        if (element.id == newVal) {
          this.svg = element;
          this.setSvg();
        }
      });
    },
  },
  beforeCreate() {
    let local_arr = LocalStorage.getAll();
    this.restaurant_id = local_arr.restaurant_id;
    this.token = local_arr.token;
    // console.log( "Restaurant Id: " + this.restaurant_id);

    fetch(
      "https://booknow-api.randion.es/api/v1/designs?filter[restaurant_id]=" +
        this.restaurant_id,
      {
        headers: {
          Accept: "application/vnd.api+json",
          Authorization: `Bearer ${this.token}`,
        },
      }
    )
      .then((res) => res.json())
      .then((response) => {
        this.svgs = response.data;
        // console.log(this.svgs);
        // TODO: Comprobar que vienen svg
        this.svg = this.svgs[0];
        this.selected = this.svgs[0].id;
        this.setOptions();
      });

    this.stopInterval = setInterval(() => {
      this.timerSvg();
    }, 5000);

    // console.log('BeforeCreate: ' + this.stopInterval);
  },
  created() {
    let usrRole = LocalStorage.getItem("usr");
    console.log(usrRole);
    if (usrRole == "admin") {
      this.admin = true;
      console.log(this.admin);
    } else {
      this.admin = false;
    }

    let today = moment().format("L").split("/");
    this.today = today[2] + "/" + today[0] + "/" + today[1];
    this.reservation.date = today[2] + "/" + today[0] + "/" + today[1];
    console.log(this.today,this.reservation.date);
  },
  beforeUnmount() {
    clearInterval(this.stopInterval);
    // console.log('UnMounted:' + this.stopInterval);
  },
  methods: {
    setOptions() {
      this.options = [];

      for (let i = 0; i < this.svgs.length; i++) {
        this.options.push(this.svgs[i].id);
      }

      let index = this.options.length;

      this.svgs.forEach((element) => {
        if (element.id == this.options[index - 1]) {
          this.svg = element;
          this.setSvg();
        }
      });
    },
    setSvg() {
      let date = new Date();
      let horas_actu = (date.getHours() < 10 ? "0" : "") + date.getHours();
      let mins_actu = (date.getMinutes() < 10 ? "0" : "") + date.getMinutes();

      let formated_h_actu = horas_actu + ":" + mins_actu;

      let svgDiv = document.getElementById("svgDiv");

      svgDiv.innerHTML = "";

      let svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");

      svg.setAttribute("width", Screen.width - 10);
      svg.setAttribute("height", Screen.height - 78);

      let tables = JSON.parse(this.svg.attributes.tables);

      for (let table in tables) {
        // console.log('Table ' + table);
        // console.log('----------------------------------------');

        let rect = document.createElementNS(
          "http://www.w3.org/2000/svg",
          "rect"
        );

        rect.setAttribute("x", tables[table].x);
        rect.setAttribute("y", tables[table].y);
        rect.setAttribute("width", tables[table].w + "px");
        rect.setAttribute("height", tables[table].h + "px");
        let ocupated_h = tables[table].ocupated_hours;
        if (ocupated_h.length == 0) {
          console.log("-------- No hay horas ocupadas ---------");
          rect.setAttribute("fill", "green");
          rect.addEventListener("click", () => {
            this.dialog = true;
            LocalStorage.set("slctbl", tables[table].number);
            this.confirmAsis = false;
            this.cancelReservation = false;
            this.ocupateTabled = true;
          });
        } else {
          // console.log(ocupated_h);
          for (let i in ocupated_h) {
            rect.setAttribute("fill", "green");
            rect.addEventListener("click", () => {
              this.dialog = true;
              LocalStorage.set("slctbl", tables[table].number);
              this.tableHoursOcupated = ocupated_h;
              this.confirmAsis = false;
              this.cancelReservation = false;
              this.ocupatedTable = true;
            });

            // console.log('Hora reserva: ' + ocupated_h[i] + " Hora actual: " + formated_h_actu);

            let arr_format_hour2 = ocupated_h[i].split(":");
            let hour_past = parseInt(arr_format_hour2) + 1;
            arr_format_hour2[0] =
              (hour_past.toString() < 10 ? "0" : "") + hour_past.toString();

            let formated_past = arr_format_hour2.join(":"); // Pasada una h

            if (
              formated_h_actu >= ocupated_h[i] &&
              formated_h_actu <= formated_past
            ) {
              console.log('Mesa ocupada',table);
              rect.setAttribute("fill", "red");
              rect.addEventListener("click", () => {
                Notify.create({
                  message: "Mesa ocupada",
                  type: "negative",
                });
                this.ocupatedTable = false;
              });
            }
            let arr_format_hour = ocupated_h[i].split(":");
            let hour_prev = parseInt(arr_format_hour) - 1;
            arr_format_hour[0] =
              (hour_prev.toString() < 10 ? "0" : "") + hour_prev.toString();
            let formated_prev = arr_format_hour.join(":");
            // console.log(formated_prev + " " + formated_h_actu + " " + ocupated_h[i]);
            if (
              formated_h_actu < ocupated_h[i] &&
              formated_h_actu > formated_prev
            ) {
              rect.setAttribute("fill", "yellow");
              rect.addEventListener("click", () => {
                Notify.create({
                  message: "Reserva a las " + ocupated_h[i],
                  type: "warning",
                });

                this.ocupatedTable = false;
                this.cancelReservation = true;
                this.confirmAsis = true;
                this.tableNextReserveHour = ocupated_h[i];
                this.tableNextReserveKey = table;
                LocalStorage.set("slctbl", tables[table].number);
              });
            }
          }
        }

        // console.log(rect);

        svg.append(rect);
      }

      // console.log(svg);

      svgDiv.appendChild(svg);
    },
    logOut() {
      LocalStorage.remove("token");
      LocalStorage.remove("usr");
      clearInterval(this.stopInterval);
      // console.log('LogOut:' + this.stopInterval);
      this.$router.push("employee-login");
    },
    timerSvg() {
      this.restaurant_id = LocalStorage.getItem("restaurant_id");
      // console.log(this.restaurant_id)
      fetch(
        "https://booknow-api.randion.es/api/v1/designs?filter[restaurant_id]=" +
          this.restaurant_id,
        {
          headers: {
            Accept: "application/vnd.api+json",
            Authorization: `Bearer ${this.token}`,
          },
        }
      )
        .then((res) => res.json())
        .then((response) => {
          // console.log(this.newSvgs,this.svgs,'pepe');

          if (this.svgs.length !== response.data.length) {
            this.svgs = response.data;
            this.setOptions();
            return;
          }

          // Si hay el mismo numero de diseños:
          for (let svg in this.svgs) {
            for (let resp_svg in response.data) {
              if (this.svgs[svg].id == response.data[resp_svg].id) {
                let svg_tables = JSON.parse(this.svgs[svg].attributes.tables);
                let respose_tables = JSON.parse(
                  response.data[resp_svg].attributes.tables
                );

                let equals = isEqual(svg_tables, respose_tables);

                if (!equals) {
                  // console.log('Son diferentes',this.svgs[svg],response.data[resp_svg]);
                  this.svgs[svg] = response.data[resp_svg];
                  console.log(this.svgs[svg].id, this.selected);
                  if (parseInt(this.svgs[svg].id) == this.selected) {
                    this.svg = this.svgs[svg];
                    this.setSvg();
                  }

                  this.$forceUpdate();
                } else {
                  // console.log("Son iguales, entrando");
                  let reservation_hours = JSON.parse(
                    this.svgs[svg].attributes.tables
                  );

                  for (let key in reservation_hours) {
                    let hours = reservation_hours[key].ocupated_hours;
                    for (let hour of hours) {
                      let date = new Date();
                      let horas_actu =
                        (date.getHours() < 10 ? "0" : "") + date.getHours();
                      let mins_actu =
                        (date.getMinutes() < 10 ? "0" : "") + date.getMinutes();

                      let formated_h_actu = horas_actu + ":" + mins_actu; // Hora actual

                      let arr_format_hour = hour.split(":");
                      let hour_prev = parseInt(arr_format_hour) - 1;
                      let fixed_hour_prev =
                        (hour_prev < 10 ? "0" : "") + hour_prev;
                      arr_format_hour[0] = fixed_hour_prev.toString();

                      let formated_prev = arr_format_hour.join(":"); // Hora previa

                      let arr_format_hour2 = hour.split(":");
                      let hour_past = parseInt(arr_format_hour2) + 1;
                      // console.log("Pasada 1 h ", hour_past);
                      let fixed_hour_past =
                        (hour_past < 10 ? "0" : "") + hour_past;
                      arr_format_hour2[0] = fixed_hour_past.toString();

                      let formated_past = arr_format_hour2.join(":"); // Pasada una h

                      // console.log("HORAS:" + formated_h_actu, formated_prev,formated_past);
                      if (
                        formated_h_actu >= formated_prev &&
                        formated_h_actu <= formated_past
                      ) {
                        // console.log("Hora entre la prev y la past");
                        if (parseInt(this.svgs[svg].id) == this.selected) {
                          // console.log("Ejecutando pintar");
                          this.setSvg();
                        }
                      } else if (formated_h_actu > formated_past) {
                        if (parseInt(this.svgs[svg].id) == this.selected) {
                          // console.log("Ejecutando pintar");
                          this.setSvg();
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        });
    },
    getHoursOnDialog() {
      this.hours = [];

      fetch(apiUrl + "/restaurants/" + this.restaurant_id, {
        method: "GET",
        headers: {
          Accept: "application/vnd.api+json",
        },
      })
        .then((res) => res.json())
        .then((resultado) => {
          this.makeReserve = true;

          this.restaurant = resultado.data.attributes;

          this.tourns = JSON.parse(this.restaurant.tourns);

          let intervalo = 30; // Meter valor en la base de datos

          for (let tourn in this.tourns) {
            let horas_int = [];

            console.log(this.tourns[tourn].start, this.tourns[tourn].end);

            let split_start = this.tourns[tourn].start.split(":");
            let split_end = this.tourns[tourn].end.split(":");
            let start =
              parseInt(split_start[0]) * 60 + parseInt(split_start[1]);
            let end = parseInt(split_end[0]) * 60 + parseInt(split_end[1]);
            console.log(start, end);

            horas_int.push(start);

            while (start <= end) {
              start = start + intervalo;
              if (start > end) {
                continue;
              }
              horas_int.push(start);
            }

            let aux_arr = [];

            for (let i = 0; i < horas_int.length; i++) {
              let horas = Math.floor(horas_int[i] / 60);
              let minutos = horas_int[i] % 60;

              let fecha = new Date();
              fecha.setHours(horas, minutos, 0, 0);

              let horaFormateada = fecha.toLocaleTimeString("es-ES", {
                hour: "2-digit",
                minute: "2-digit",
              });

              if (!this.tableHoursOcupated.includes(horaFormateada)) {
                aux_arr.push(horaFormateada);
              }

              // console.log(horaFormateada);
            }
            this.hours.push([this.tourns[tourn].tourn_name, aux_arr]);
          }

          console.log(this.hours);
        })
        .catch(() => {
          Notify.create({
            message: "Ha habido un error, intenta de nuevo!",
            type: "warning",
          });
        });
    },
    createReservation() {
      this.loadingBtnCR = true
      this.reservation.table = LocalStorage.getItem("slctbl");
      this.reservation.code =
        Math.floor(Math.random() * (999999999999 - 100000000000 + 1)) +
        100000000000;
      this.reservation.dsid = this.svg.id;
      this.reservation.resid = this.restaurant_id;
      this.verifyEmail(this.reservation.userEmail)

      setTimeout(() => {
        console.log(this.verify);
        if (this.verify) {
          console.log("Entra");
          setTimeout(() => {
            console.log(this.reservation);
            let token = LocalStorage.getItem("token");
            fetch(apiUrl + "/reservations", {
              method: "POST",
              headers: {
                "Accept": "application/vnd.api+json",
                "Content-Type": "application/vnd.api+json",
                "Authorization": `Bearer ${token}`,
              },
              body: JSON.stringify({
                data: {
                  attributes: {
                    reservation_code: this.reservation.code,
                    date: this.reservation.date,
                    hour: this.reservation.hour,
                    diners: this.reservation.diners,
                    table_number: this.reservation.table,
                    user_id: parseInt(this.reservation.usrid),
                    restaurant_id: parseInt(this.reservation.resid),
                    design_id: parseInt(this.reservation.dsid),
                  },
                },
              }),
            })
              .then((res) => res.json())
              .then((response) => {
                console.log(response);
  
                this.makeReserve = false;
  
                Notify.create({
                  message: "Created Reservation",
                  type: "positive",
                });

                this.loadingBtnCR = false
                this.verify = false

                this.reservation.hour = ""
                this.reservation.code = "",
                this.reservation.diners = "",
                this.reservation.table = "",
                this.reservation.dsid = 0,
                this.reservation.resid = 0,
                this.reservation.userEmail = "",
                this.reservation.usrid = 0
              })
              .catch((error) => {
                console.log(error);
              });
          }, 1000);
  
        } else {
          Notify.create({
            message: "El usuario no existe,registralo para iniciar sessión.",
            type: "negative",
          });
  
          this.makeReserve = false;
        }
      },1000)
    },
    verifyEmail(email) {
      console.log("DOING VERIFY EMAIL");
      fetch(apiUrl + "/users?filter[email]=" + email, {
        headers: {
          Accept: "application/vnd.api+json",
        },
        method: "GET",  
      }).then((res) => res.json()).then((response) => {

        if (response.data.length != 0) {
          this.reservation.usrid = parseInt(response.data[0].id);
          this.verify = true
        } else {
          // TODO: Si el usuario no existe, dar la opcion de crear uno y almacenar los en LocalStorage de la reserva
          // this.dlNoUserExist = true
        }
        
      });
    },
    setDiners(diners_num) {
      this.reservation.diners = diners_num;
    },
    updateHour(hora) {
      this.reservation.hour = hora;
    },
    confirmTableAssistance() {

      let actHour = this.getActualHour()
      let number = LocalStorage.getItem('slctbl');
      let token = LocalStorage.getItem("token_rest");
      console.log(this.tableNextReserveHour, this.tableNextReserveKey,actHour,this.selected);

      fetch(`${apiUrl}/designs/confassis/${this.selected}?filter[design_id]=${this.selected}&filter[table_number]=${number}&filter[hour]=${this.tableNextReserveHour}`,{

        method : 'PATCH',
        headers : {

          "Accept" : "application/vnd.api+json",
          "Content-Type" : "application/vnd.api+json",
          "Authorization" : `Bearer ${token}`
        },
        body : JSON.stringify({

          next : this.tableNextReserveHour,
          actual : actHour,
          table : this.tableNextReserveKey

        })
      }).then((res) => res.json()).then((response) => {


        console.log(response);

      })
    },
    cancelActualReservation() {
      let number = LocalStorage.getItem('slctbl');
      let token = LocalStorage.getItem("token");
      // TODO: Eliminar la hora en las horas ocupadas de la mesa y eliminar reserva
      fetch(apiUrl + "/reservations?filter[design_id]=" + this.selected + "&filter[table_number]=" + number + "&filter[hour]=" + this.tableNextReserveHour + "&filter[date]=" + this.today,{
        headers : {
          'Accept' : 'application/vnd.api+json',
          "Authorization" : `Bearer ${token}`
        }
      })
      .then((res) => res.json())
      .then((response) => {

        let reservation = response.data[0]
        console.log(reservation);
        if(this.today == reservation.attributes.date){

          fetch(apiUrl + "/designs/removehour/" + reservation.attributes.design_id , {
          headers: {
            "Accept" : "application/vnd.api+json",
            "Content-Type" : "application/vnd.api+json",
            Authorization: `Bearer ${token}`,
          },
          method: "PATCH",
          body: JSON.stringify({

            table: reservation.attributes.table_number,
            hour : reservation.attributes.hour

          })
          }).then((res) => res.json()).then((response) => {

            console.log(response);

            this.deleteReservation(reservation.id)


          }).catch((error) => {
            console.log(error);
          });
        } else {

          this.deleteReservation(reservation.id)
        }

      })
    },
    ocupateTable() {
      // TODO:
    },
    getActualHour() {
      let date = new Date();
      let horas_actu = (date.getHours() < 10 ? "0" : "") + date.getHours();
      let mins_actu = (date.getMinutes() < 10 ? "0" : "") + date.getMinutes();

      return horas_actu + ":" + mins_actu; // Hora actual
    },
    hourCompareActual(hour){

      let hourActual = this.getActualHour() // Hora actual

      let splitHourActual = hourActual.split(":")
      let numMinsHourActual = (parseInt(splitHourActual[0]) * 60) + parseInt(splitHourActual[1])

      let splitHour = hour.split(":")
      let numMinsHour = (parseInt(splitHour[0]) * 60) + parseInt(splitHour[1])

      return numMinsHour > numMinsHourActual

    },
    deleteReservation(reservation_id){

    let token = LocalStorage.getItem("token");

      fetch(apiUrl + "/reservations/" + reservation_id, {
        headers: {
          Accept: "application/vnd.api+json",
          Authorization: `Bearer ${token}`,
        },
        method: "DELETE",
      }).then((res) => {

        Notify.create({

          message: "Reserva Eliminada",
          type: "positive"
        })

        console.log(res);

      }).catch((error) => {
        console.log(error);
      });

    }
  },
};
</script>
<style scoped>
rect {
  border: 3px black solid;
}
.diners-btn {
  background-color: #00897b;
  color: #ffffff;
  margin: 4px;
  border-radius: 8px;
  text-transform: uppercase;
  padding: 10px 20px;
  transition: background-color 0.3s;
}

.diners-btn:hover {
  background-color: #00695c;
}
.boton {
  width: 20%;
}
.hora-btn {
  background-color: #1e88e5;
  color: #ffffff;
  margin: 4px;
  border-radius: 8px;
  text-transform: uppercase;
  padding: 10px 20px;
  transition: background-color 0.3s;
}

.hora-btn:hover {
  background-color: #1565c0;
}
</style>
