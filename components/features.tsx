import { Truck, Clock, Award, Leaf } from "lucide-react"

const features = [
  {
    icon: Truck,
    title: "Entrega rápida",
    description: "Recibe tu pedido en 30 minutos o es gratis",
  },
  {
    icon: Clock,
    title: "Abierto 24/7",
    description: "Disponibles todos los días, a cualquier hora",
  },
  {
    icon: Award,
    title: "Calidad garantizada",
    description: "Ingredientes frescos y de primera calidad",
  },
  {
    icon: Leaf,
    title: "Opciones saludables",
    description: "Menú con alternativas vegetarianas y veganas",
  },
]

export function Features() {
  return (
    <section id="nosotros" className="py-20 md:py-32">
      <div className="container mx-auto px-4">
        <div className="mb-12 flex flex-col gap-4 text-center">
          <h2 className="font-sans text-4xl font-bold text-balance text-foreground md:text-5xl">¿Por qué elegirnos?</h2>
          <p className="mx-auto max-w-2xl font-sans text-lg leading-relaxed text-muted-foreground">
            Nos comprometemos a brindarte la mejor experiencia en comida rápida
          </p>
        </div>

        <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
          {features.map((feature) => (
            <div key={feature.title} className="flex flex-col items-center gap-4 text-center">
              <div className="flex h-20 w-20 items-center justify-center rounded-2xl bg-primary/10">
                <feature.icon className="h-10 w-10 text-primary" />
              </div>
              <div>
                <h3 className="mb-2 font-sans text-xl font-bold text-foreground">{feature.title}</h3>
                <p className="font-sans leading-relaxed text-muted-foreground">{feature.description}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
