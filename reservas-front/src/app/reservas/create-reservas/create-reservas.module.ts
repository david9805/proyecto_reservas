import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CreateReservasComponent } from './create-reservas.component';
import { RouterModule, Routes } from '@angular/router';
import { ReactiveFormsModule } from '@angular/forms';

const routes:Routes = [
  {
    path:'',
    component:CreateReservasComponent
  },
  {
    path:':id',
    component:CreateReservasComponent
  }
]

@NgModule({
  declarations: [
    CreateReservasComponent
  ],
  imports: [
    CommonModule,
    RouterModule.forChild(routes),
    ReactiveFormsModule
  ]
})
export class CreateReservasModule { }
