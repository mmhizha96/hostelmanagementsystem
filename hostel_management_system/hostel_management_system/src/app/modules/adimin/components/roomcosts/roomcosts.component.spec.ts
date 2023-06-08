import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RoomcostsComponent } from './roomcosts.component';

describe('RoomcostsComponent', () => {
  let component: RoomcostsComponent;
  let fixture: ComponentFixture<RoomcostsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RoomcostsComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RoomcostsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
