const { createApp } = Vue;

      // Monta a aplicação Vue no elemento #app
      const app = createApp({
        // ---------------------------------------------------------------------
        // DADOS (STATE)
        // ---------------------------------------------------------------------
        data() {
          return {
            // !!! MUDE AQUI SE NECESSÁRIO !!!
            apiBaseUrl: "https://backend-9wh0.onrender.com/api", // Sem barra no final

            // --- Controle da UI ---
            currentMode: "pesquisa", // 'pesquisa' (M1/M2) ou 'captura' (M3)
            currentView: "pesquisa-logs",
            loading: true,
            error: null,
            modal: { type: null, data: null }, // Controla todos os modais

            // --- Listas de Dados (M1 & M2) ---
            logs: [],
            ducks: [],
            surveyDrones: [], // Drones de Pesquisa
            manufacturers: [],

            // --- Listas de Dados (M3) ---
            missions: [],
            captureDrones: [], // Drones de Captura
            weaknesses: [],
            strategies: [],
            defenses: [],

            // --- Formulários ---
            editFormData: null, // Objeto genérico para formulários de edição
            newManufacturerForm: { name: "", country_of_origin: "" },
            newSurveyDroneForm: {
              serial_number: "",
              brand: "",
              manufacturer_id: "",
            },
            newSightingForm: this.getEmptySightingForm(),
            newCaptureDroneForm: { designation: "CD-001" }, // M3
            newWeaknessForm: { name: "", description: "" }, // M3
            newStrategyForm: {
              name: "",
              description: "",
              trigger_logic:
                '{\n  "field": "height_cm",\n  "operator": ">",\n  "value": 100\n}',
            }, // M3
            newDefenseForm: { name: "", description: "", resource_cost: 10 }, // M3
            newMissionForm: {
              capture_drone_id: "",
              primordial_duck_id: "",
              briefing_notes: "",
            }, // M3
            telemetryForm: {
              drone_id: null,
              battery_percent: 100,
              fuel_percent: 100,
              integrity_percent: 100,
              status: "em_missao",
            }, // M3
          };
        },

        // ---------------------------------------------------------------------
        // DADOS COMPUTADOS (Getters)
        // ---------------------------------------------------------------------
        computed: {
          // Filtra drones de captura que estão 'ociosos' para o formulário de nova missão
          availableCaptureDrones() {
            return this.captureDrones.filter((d) => d.status === "ocioso");
          },
        },

        // ---------------------------------------------------------------------
        // MÉTODOS (ACTIONS)
        // ---------------------------------------------------------------------
        methods: {
          // --- Helpers ---
          formatStatus(status) {
            if (!status) return "N/A";
            return status.replace(/_/g, " "); // Troca underscore por espaço
          },
          formatDateTime(isoString) {
            if (!isoString) return "N/A";
            try {
              return new Date(isoString).toLocaleString("pt-BR");
            } catch (e) {
              return "Data inválida";
            }
          },

          // --- Controle da UI ---
          changeMode(mode) {
            this.currentMode = mode;
            this.error = null;
            // Define a view padrão para o modo
            if (mode === "pesquisa") {
              this.currentView = "pesquisa-logs";
            } else {
              this.currentView = "captura-missions";
            }
          },
          changeView(view) {
            this.currentView = view;
            this.error = null; // Limpa erros ao trocar de aba
          },

          // DENTRO DO <script> ... methods: { ... } NO SEU index.html

          async openModal(type, data) {
            // Log Inicial: Qual modal estamos abrindo?
            console.log(
              `[openModal] Iniciando. Tipo: ${type}, Dados recebidos:`,
              data
            );

            this.modal.type = type;
            this.loading = true; // Mostra loading global
            this.modal.data = null; // Reseta dados do modal anterior
            this.editFormData = null; // Reseta form de edição

            try {
              if (
                [
                  "editManufacturer",
                  "editSurveyDrone",
                  "editCaptureDrone",
                  "editWeakness",
                  "editDefense",
                ].includes(type)
              ) {
                // Clona o objeto para evitar reatividade indesejada no formulário
                this.editFormData = { ...data };
                console.log(
                  "[openModal] Formulário de edição preparado:",
                  this.editFormData
                );
              } else if (type === "editStrategy") {
                // Converte o objeto 'trigger_logic' em string JSON formatada
                this.editFormData = {
                  ...data,
                  trigger_logic: data.trigger_logic
                    ? JSON.stringify(data.trigger_logic, null, 2)
                    : "",
                };
                console.log(
                  "[openModal] Formulário de edição (Estratégia) preparado:",
                  this.editFormData
                );
              } else if (type === "telemetry") {
                // Configura o formulário de telemetria
                this.telemetryForm = {
                  drone_id: data.id,
                  battery_percent: data.battery_percent,
                  fuel_percent: data.fuel_percent,
                  integrity_percent: data.integrity_percent,
                  status: data.status,
                };
                this.modal.data = data; // Guarda o drone para exibir o nome
                console.log(
                  "[openModal] Formulário de Telemetria preparado. Drone:",
                  data
                );
              } else if (type === "missionDetails") {
                // 'data' é o ID da missão. Busca os dados completos.
                console.log(
                  `[openModal] Buscando detalhes para Missão ID: ${data}`
                );
                const missionData = await this.apiRequest(
                  `/capture-missions/${data}`
                );
                console.log(
                  "[openModal] Resposta da API /capture-missions/{id}:",
                  missionData
                ); // LOG IMPORTANTE
                if (!missionData || !missionData.data)
                  throw new Error(
                    "Não foi possível carregar detalhes da missão ou formato inválido."
                  );
                this.modal.data = missionData.data;
                console.log(
                  "[openModal] Dados da Missão salvos em modal.data:",
                  this.modal.data
                );
              } else if (type === "tacticalPlan") {
                // 'data' é o objeto Pato. Busca o plano tático.
                console.log(
                  "[openModal] Abrindo Plano Tático para Pato:",
                  data
                ); // Log 1 (Revisado)
                if (!data || !data.designation) {
                  console.error(
                    "[openModal] Objeto Pato inválido recebido para Plano Tático:",
                    data
                  );
                  throw new Error(
                    "Dados do Pato inválidos para buscar plano tático."
                  );
                }
                const planData = await this.apiRequest(
                  `/primordial-ducks/${data.designation}/tactical-plan`
                );

                console.log(
                  "[openModal] Resposta da API /tactical-plan:",
                  planData
                ); // Log 2 (Revisado)

                if (!planData) {
                  console.error(
                    "[openModal] Falha ao buscar plano tático da API!"
                  ); // Log 3 (Revisado)
                  throw new Error("Não foi possível carregar o plano tático.");
                }

                // Verifica se as chaves esperadas existem ANTES de salvar
                // Ajuste: Permite que as chaves existam mas sejam null ou vazias
                if (
                  typeof planData !== "object" ||
                  planData === null ||
                  !planData.hasOwnProperty("known_weaknesses") ||
                  !planData.hasOwnProperty("recommended_strategies")
                ) {
                  console.error(
                    "[openModal] Resposta da API /tactical-plan tem formato inesperado:",
                    planData
                  ); // Log 4 (Revisado)
                  throw new Error(
                    "Formato de plano tático inesperado recebido da API."
                  );
                }
                // Garante que sejam arrays (mesmo que nulos/vazios) para o template
                const finalPlanData = {
                  known_weaknesses: Array.isArray(planData.known_weaknesses)
                    ? planData.known_weaknesses
                    : [],
                  recommended_strategies: Array.isArray(
                    planData.recommended_strategies
                  )
                    ? planData.recommended_strategies
                    : [],
                };

                this.modal.data = {
                  duckDesignation: data.designation,
                  plan: finalPlanData, // Salva os dados já verificados/formatados
                };
                console.log(
                  "[openModal] Dados do Plano Tático salvos em modal.data:",
                  this.modal.data
                ); // Log 5 (Revisado)
              } else if (type === "duckDetails") {
                console.log(
                  "[openModal] Buscando detalhes para Pato:",
                  data?.designation
                );
                if (!data || !data.designation) {
                  console.error(
                    "[openModal] Objeto Pato inválido recebido para Detalhes:",
                    data
                  );
                  throw new Error(
                    "Dados do Pato inválidos para buscar detalhes."
                  );
                }
                const duckData = await this.apiRequest(
                  `/primordial-ducks/${data.designation}`
                );
                console.log(
                  "[openModal] Resposta da API /primordial-ducks/{designation}:",
                  duckData
                );
                if (!duckData || !duckData.data)
                  throw new Error(
                    "Não foi possível carregar detalhes do pato ou formato inválido."
                  );
                this.modal.data = duckData.data;
                console.log(
                  "[openModal] Dados do Pato salvos em modal.data:",
                  this.modal.data
                );
              } else {
                // Modais simples (filteredLogs, defenseResponse)
                this.modal.data = data;
                console.log(
                  "[openModal] Dados para modal simples salvos:",
                  this.modal.data
                );
              }
            } catch (modalError) {
              console.error(
                "[openModal] Erro durante a abertura/processamento do modal:",
                modalError
              ); // Log de Erro Geral
              this.error = `Erro ao abrir modal [${type}]: ${modalError.message}`;
              this.closeModal(); // Fecha o modal se houve erro
            } finally {
              console.log(`[openModal] Finalizando. Loading: false.`);
              this.loading = false; // Garante que loading termine
            }
          }, // Fim da função openModal

          closeModal() {
            this.modal = { type: null, data: null };
            this.editFormData = null; // Limpa o formulário de edição
          },

          resetForms() {
            // M1
            this.newManufacturerForm = { name: "", country_of_origin: "" };
            this.newSurveyDroneForm = {
              serial_number: "",
              brand: "",
              manufacturer_id: "",
            };
            this.newSightingForm = this.getEmptySightingForm();
            // M3
            this.newCaptureDroneForm = {
              designation: `CD-00${this.captureDrones.length + 1}`,
            };
            this.newWeaknessForm = { name: "", description: "" };
            this.newStrategyForm = {
              name: "",
              description: "",
              trigger_logic:
                '{\n  "field": "height_cm",\n  "operator": ">",\n  "value": 100\n}',
            };
            this.newDefenseForm = {
              name: "",
              description: "",
              resource_cost: 10,
            };
            this.newMissionForm = {
              capture_drone_id: "",
              primordial_duck_id: "",
              briefing_notes: "",
            };
          },
          getEmptySightingForm() {
            // Helper para resetar o formulário complexo (M1)
            return {
              drone_serial_number: "",
              designation: "",
              height: "",
              height_unit: "cm",
              weight: "",
              weight_unit: "g",
              latitude: "",
              longitude: "",
              gps_precision: "",
              gps_precision_unit: "m",
              last_known_city: "",
              last_known_country: "",
              reference_point: "",
              hibernation_status: "desperto",
              heart_rate_bpm: null,
              mutation_count: "",
              superpower_name: "",
              superpower_description: "",
              superpower_classifications: "",
            };
          },

          // --- Requisições (Fetch) ---
          async apiRequest(endpoint, method = "GET", body = null) {
            this.loading = true;
            this.error = null;
            let responseData = null; // Variável para retornar
            try {
              const options = {
                method,
                headers: {
                  "Content-Type": "application/json",
                  Accept: "application/json",
                },
              };
              if (body) {
                options.body = JSON.stringify(body);
              }

              const response = await fetch(
                `${this.apiBaseUrl}${endpoint}`,
                options
              );

              if (!response.ok) {
                const errorData = await response
                  .json()
                  .catch(() => ({ message: response.statusText })); // Tenta pegar JSON, senão usa statusText
                let errorMsg = `Erro ${response.status}: ${
                  errorData.message || response.statusText
                }`;
                if (errorData.errors) {
                  errorMsg +=
                    " Detalhes: " +
                    Object.values(errorData.errors).flat().join(" ");
                }
                throw new Error(errorMsg);
              }

              if (response.status === 204 || response.status === 202) {
                responseData = true; // Indica sucesso sem corpo
              } else {
                responseData = await response.json();
              }
            } catch (err) {
              this.error = err.message; // Mostra o erro na UI
              console.error("API Request Failed:", err); // Loga no console também
              responseData = null; // Indica falha
            } finally {
              this.loading = false;
            }
            return responseData; // Retorna os dados ou null/true
          },

          // --- Métodos de Busca (GET) ---
          async fetchAllData() {
            // Carrega todos os dados de TODAS as missões de uma vez
            this.loading = true;
            await Promise.all([
              // M1/M2
              this.fetchLogs(),
              this.fetchDucks(),
              this.fetchSurveyDrones(),
              this.fetchManufacturers(),
              // M3
              this.fetchCaptureMissions(),
              this.fetchCaptureDrones(),
              this.fetchWeaknesses(),
              this.fetchStrategies(),
              this.fetchDefenses(),
            ]);
            this.loading = false;
          },
          // M1/M2 Getters
          async fetchLogs() {
            const data = await this.apiRequest("/sighting-logs");
            if (data) this.logs = data.data; // Vem de Resource
          },
          async fetchDucks() {
            const data = await this.apiRequest("/primordial-ducks");
            if (data) this.ducks = data.data; // Vem de Resource
          },
          async fetchSurveyDrones() {
            const data = await this.apiRequest("/survey-drones");
            if (data) this.surveyDrones = data.data || []; // <-- AGORA lê de data.data
          },
          async fetchManufacturers() {
            const data = await this.apiRequest("/manufacturer");
            if (data) this.manufacturers = data.fabricantes || data.data || [];
          },
          async fetchLogsForDuck(designation) {
            const data = await this.apiRequest(
              `/primordial-ducks/${designation}/logs`
            );
            if (
              data &&
              data.PatoPrimordial &&
              data.PatoPrimordial.duck_sighting_log
            ) {
              this.openModal("filteredLogs", {
                title: `Logs para o Pato: ${designation}`,
                logs: data.PatoPrimordial.duck_sighting_log,
              });
            } else {
              this.error = `Não foi possível carregar logs para ${designation}.`;
            }
          },
          async fetchLogsForDrone(serialNumber) {
            const data = await this.apiRequest(
              `/survey-drones/${serialNumber}/logs`
            );
            if (data && data.LogsAvistamento) {
              this.openModal("filteredLogs", {
                title: `Logs do Drone: ${serialNumber}`,
                logs: data.LogsAvistamento,
              });
            } else {
              this.error = `Não foi possível carregar logs para ${serialNumber}.`;
            }
          },

          // M3 Getters
          async fetchCaptureMissions() {
            const data = await this.apiRequest("/capture-missions");
            if (data) this.missions = data.data || [];
          },
          async fetchCaptureDrones() {
            const data = await this.apiRequest("/capture-drones");
            if (data)
              this.captureDrones = data.DronesDeCaptura || data.data || [];
          },
          async fetchWeaknesses() {
            const data = await this.apiRequest("/weaknesses");
            if (data) this.weaknesses = data.Fraquezas || data.data || [];
          },
          async fetchStrategies() {
            const data = await this.apiRequest("/attack-strategies");
            if (data)
              this.strategies = data.EstrategiasDeAtaque || data.data || [];
          },
          async fetchDefenses() {
            const data = await this.apiRequest("/defense-systems");
            if (data) this.defenses = data.SistemasDeDefesa || data.data || [];
          },

          // --- M1/M2 Métodos de Ação (POST/PUT/DELETE) ---
          async submitNewSighting() {
            let payload = { ...this.newSightingForm };
            if (payload.hibernation_status === "desperto") {
              if (payload.superpower_classifications) {
                // Verifica se não está vazio
                payload.superpower_classifications =
                  payload.superpower_classifications
                    .split(",")
                    .map((s) => s.trim());
              } else {
                payload.superpower_classifications = []; // Envia array vazio se não preenchido
              }
              delete payload.heart_rate_bpm;
            } else {
              delete payload.superpower_name;
              delete payload.superpower_description;
              delete payload.superpower_classifications;
            }
            const data = await this.apiRequest("/sightings", "POST", payload);
            if (data) {
              alert(
                `Avistamento do Pato '${data.data.designation}' registrado! Análise (M2) gerada.`
              );
              this.resetForms();
              await this.fetchLogs();
              await this.fetchDucks(); // Recarrega patos para ver nova análise
              this.changeView("pesquisa-ducks");
            }
          },
          async submitNewSurveyDrone() {
            const data = await this.apiRequest(
              "/survey-drones",
              "POST",
              this.newSurveyDroneForm
            );
            if (data) {
              alert("Drone de Pesquisa criado!");
              this.resetForms();
              await this.fetchSurveyDrones();
            }
          },
          async submitEditSurveyDrone() {
            const data = await this.apiRequest(
              `/survey-drones/${this.editFormData.id}`,
              "PUT",
              this.editFormData
            );
            if (data) {
              alert("Drone de Pesquisa atualizado!");
              this.closeModal();
              await this.fetchSurveyDrones();
            }
          },
          async deleteSurveyDrone(id) {
            if (confirm("Tem certeza?")) {
              const success = await this.apiRequest(
                `/survey-drones/${id}`,
                "DELETE"
              );
              if (success) {
                alert("Drone de Pesquisa excluído!");
                await this.fetchSurveyDrones();
              }
            }
          },
          async submitNewManufacturer() {
            const data = await this.apiRequest(
              "/manufacturer",
              "POST",
              this.newManufacturerForm
            );
            if (data) {
              alert("Fabricante criado!");
              this.resetForms();
              await this.fetchManufacturers();
            }
          },
          async submitEditManufacturer() {
            const data = await this.apiRequest(
              `/manufacturer/${this.editFormData.id}`,
              "PUT",
              this.editFormData
            );
            if (data) {
              alert("Fabricante atualizado!");
              this.closeModal();
              await this.fetchManufacturers();
            }
          },
          async deleteManufacturer(id) {
            if (confirm("Tem certeza? Isso pode afetar drones.")) {
              const success = await this.apiRequest(
                `/manufacturer/${id}`,
                "DELETE"
              );
              if (success) {
                alert("Fabricante excluído!");
                await this.fetchManufacturers();
                await this.fetchSurveyDrones();
              }
            }
          },

          // --- M3 Métodos de Ação (POST/PUT/DELETE) ---

          // --- Missões
          async submitNewMission() {
            const data = await this.apiRequest(
              "/capture-missions",
              "POST",
              this.newMissionForm
            );
            if (data) {
              alert(
                `Missão #${data.data.id} lançada! Status: ${data.data.status}`
              );
              this.resetForms();
              await this.fetchCaptureMissions();
              await this.fetchCaptureDrones(); // Status do drone mudou
            }
          },
          async activateDefense(missionId) {
            const data = await this.apiRequest(
              `/capture-missions/${missionId}/activate-defense`,
              "POST"
            );
            if (data) {
              this.openModal("defenseResponse", data);
            } else {
              // Se apiRequest retornar null (por causa de 404, por exemplo), mostramos o erro
              // A API já retorna 404 com a mensagem correta, então o this.error já foi setado
              // alert(this.error || "Não foi possível ativar a defesa."); // Podemos mostrar um alerta se preferir
            }
          },
          async submitTelemetry() {
            const droneId = this.telemetryForm.drone_id;
            const payload = {
              battery_percent: this.telemetryForm.battery_percent,
              fuel_percent: this.telemetryForm.fuel_percent,
              integrity_percent: this.telemetryForm.integrity_percent,
              status: this.telemetryForm.status,
            };
            const data = await this.apiRequest(
              `/capture-drones/${droneId}/telemetry`,
              "POST",
              payload
            );
            if (data) {
              alert(data.message);
              this.closeModal();
              await this.fetchCaptureDrones(); // Atualiza lista de drones
              // Se o modal de missão estiver aberto, re-busca os dados
              if (this.modal.type === "missionDetails") {
                const currentMissionId = this.modal.data.id; // Guarda o ID
                this.loading = true; // Mostra loading no modal
                const missionData = await this.apiRequest(
                  `/capture-missions/${currentMissionId}`
                );
                if (missionData) {
                  this.modal.data = missionData.data; // Atualiza os dados
                } else {
                  this.closeModal(); // Fecha se não encontrar mais a missão
                }
                this.loading = false;
              }
            }
          },

          // --- CRUD Drone de Captura
          async submitNewCaptureDrone() {
            // Define valores padrão no frontend ao criar
            const payload = {
              ...this.newCaptureDroneForm,
              status: "ocioso",
              battery_percent: (100).toFixed(2), // Format to "100.00"
              fuel_percent: (100).toFixed(2), // Format to "100.00"
              integrity_percent: (100).toFixed(2), // Format to "100.00"
            };
            const data = await this.apiRequest(
              "/capture-drones",
              "POST",
              payload
            );
            if (data) {
              alert("Drone de Captura criado!");
              this.resetForms();
              await this.fetchCaptureDrones();
            }
          },
          async submitEditCaptureDrone() {
            // PUT envia o objeto completo, PATCH pode enviar só o que mudou
            const data = await this.apiRequest(
              `/capture-drones/${this.editFormData.id}`,
              "PUT",
              this.editFormData
            );
            if (data) {
              alert("Drone de Captura atualizado!");
              this.closeModal();
              await this.fetchCaptureDrones();
            }
          },
          async deleteCaptureDrone(id) {
            if (confirm("Tem certeza?")) {
              const success = await this.apiRequest(
                `/capture-drones/${id}`,
                "DELETE"
              );
              if (success) {
                alert("Drone de Captura excluído!");
                await this.fetchCaptureDrones();
              }
            }
          },

          // --- CRUD Arsenal: Fraquezas
          async submitNewWeakness() {
            const data = await this.apiRequest(
              "/weaknesses",
              "POST",
              this.newWeaknessForm
            );
            if (data) {
              alert("Fraqueza cadastrada!");
              this.resetForms();
              await this.fetchWeaknesses();
            }
          },
          async submitEditWeakness() {
            const data = await this.apiRequest(
              `/weaknesses/${this.editFormData.id}`,
              "PUT",
              this.editFormData
            );
            if (data) {
              alert("Fraqueza atualizada!");
              this.closeModal();
              await this.fetchWeaknesses();
            }
          },
          async deleteWeakness(id) {
            if (confirm("Tem certeza?")) {
              const success = await this.apiRequest(
                `/weaknesses/${id}`,
                "DELETE"
              );
              if (success) {
                alert("Fraqueza excluída!");
                await this.fetchWeaknesses();
              }
            }
          },

          // --- CRUD Arsenal: Estratégias
          async submitNewStrategy() {
            let payload = { ...this.newStrategyForm };
            try {
              // Valida e converte o JSON antes de enviar, ou envia null se vazio
              payload.trigger_logic = this.newStrategyForm.trigger_logic.trim()
                ? JSON.parse(this.newStrategyForm.trigger_logic)
                : null;
            } catch (e) {
              this.error = "Lógica de Gatilho (JSON) é inválida! " + e.message;
              return;
            }
            const data = await this.apiRequest(
              "/attack-strategies",
              "POST",
              payload
            );
            if (data) {
              alert("Estratégia cadastrada!");
              this.resetForms();
              await this.fetchStrategies();
            }
          },
          async submitEditStrategy() {
            let payload = { ...this.editFormData };
            try {
              // Valida e converte o JSON
              payload.trigger_logic = this.editFormData.trigger_logic.trim()
                ? JSON.parse(this.editFormData.trigger_logic)
                : null;
            } catch (e) {
              this.error = "Lógica de Gatilho (JSON) é inválida! " + e.message;
              return;
            }
            const data = await this.apiRequest(
              `/attack-strategies/${payload.id}`,
              "PUT",
              payload
            );
            if (data) {
              alert("Estratégia atualizada!");
              this.closeModal();
              await this.fetchStrategies();
            }
          },
          async deleteStrategy(id) {
            if (confirm("Tem certeza?")) {
              const success = await this.apiRequest(
                `/attack-strategies/${id}`,
                "DELETE"
              );
              if (success) {
                alert("Estratégia excluída!");
                await this.fetchStrategies();
              }
            }
          },

          // --- CRUD Arsenal: Defesas
          async submitNewDefense() {
            const data = await this.apiRequest(
              "/defense-systems",
              "POST",
              this.newDefenseForm
            );
            if (data) {
              alert("Sistema de Defesa cadastrado!");
              this.resetForms();
              await this.fetchDefenses();
            }
          },
          async submitEditDefense() {
            const data = await this.apiRequest(
              `/defense-systems/${this.editFormData.id}`,
              "PUT",
              this.editFormData
            );
            if (data) {
              alert("Sistema de Defesa atualizado!");
              this.closeModal();
              await this.fetchDefenses();
            }
          },
          async deleteDefense(id) {
            if (confirm("Tem certeza?")) {
              const success = await this.apiRequest(
                `/defense-systems/${id}`,
                "DELETE"
              );
              if (success) {
                alert("Sistema de Defesa excluído!");
                await this.fetchDefenses();
              }
            }
          },
        },

        // ---------------------------------------------------------------------
        // CICLO DE VIDA (LIFECYCLE)
        // ---------------------------------------------------------------------
        async mounted() {
          // Carrega os dados iniciais assim que o app for montado
          await this.fetchAllData();
          this.loading = false;
        },
      });

      app.mount("#app");